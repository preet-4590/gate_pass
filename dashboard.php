<?php
include('db_config.php');

if (!isset($_SESSION['clerk_user'])) {
    header("Location: login.php");
    exit;
}

$inst = $_SESSION['clerk_inst'];
// Ensure this matches your Ngrok terminal exactly
$site_url = "https://glandular-barcode-kitten.ngrok-free.dev";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard |
        <?php echo $inst; ?>
    </title>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --light: #f4f7f6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            margin: 0;
            background: var(--light);
            color: #333;
        }

        .nav {
            background: var(--primary);
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .user-info h3 a {
            text-decoration: none;
            color: white;
            transition: 0.3s;
        }

        .user-info h3 a:hover {
            color: #cccccc !important;
            opacity: 0.8;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .btn-add {
            background: var(--success);
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-logout {
            background: var(--danger);
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }

        .container {
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }

        .search-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            gap: 15px;
        }

        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            outline: none;
        }

        .btn-search {
            background: var(--accent);
            color: white;
            border: none;
            padding: 0 30px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background: #ecf0f1;
            color: var(--primary);
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .view-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .delete-link {
            color: var(--danger);
            text-decoration: none;
            font-weight: 600;
            margin-left: 10px;
        }

        .qr-thumb {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="nav">
        <div class="user-info">
            <h3><a href="dashboard.php">Dashboard:
                    <?php echo $inst; ?>
                </a></h3>
            <small>Logged in as:
                <?php echo $_SESSION['clerk_user']; ?>
            </small>
        </div>
        <div class="nav-links">
            <a href="add_student.php" class="btn-add">+ Register Student</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div
                style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <strong>Record Deleted:</strong> The student has been permanently removed from the system.
            </div>
        <?php endif; ?>

        <div class="search-section">
            <form class="search-form" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search by name, roll no, or ID..."
                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="btn-search">Search Records</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Unique ID</th>
                    <th>Name</th>
                    <th>Roll No</th>
                    <th>Course</th>
                    <th>QR Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');

                // 1. Simplified query: removed is_active filter
                $query = "SELECT * FROM students WHERE institution = '$inst'";

                if (!empty($search)) {
                    $query .= " AND (name LIKE '%$search%' OR roll_no LIKE '%$search%' OR unique_id LIKE '%$search%')";
                }

                $query .= " ORDER BY unique_id DESC";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $raw_id = $row['unique_id'];
                        $encoded_id = urlencode($raw_id);

                        // 2. Fix: Use the local QR path from your database instead of Google API[cite: 2, 5]
                        $local_qr_path = $row['qr_path'];

                        echo "<tr>
                        <td><strong>{$raw_id}</strong></td>
                        <td>{$row['name']}</td>
                        <td>{$row['roll_no']}</td>
                        <td>{$row['course']}</td>
                        <td>
                                <a href='{$local_qr_path}' target='_blank'>
                                    <img src='{$local_qr_path}' class='qr-thumb' alt='QR Code'>
                                </a>
                            </td>
                            <td>
                                <a href='view_profile.php?id={$encoded_id}' class='view-link'>View Profile</a>
                            </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; padding: 40px;'>No student records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>