<?php
include('db_config.php');

// Security check: Only allow Super Admin
if (!isset($_SESSION['clerk_user']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: login.php");
    exit;
}

$site_url = "https://glandular-barcode-kitten.ngrok-free.dev"; // Update as needed
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard | All Records</title>
    <style>
        /* Exact same CSS from your dashboard.php[cite: 2] */
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

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
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

        .inst-badge {
            background: #e1e8ed;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: var(--primary);
        }

        .qr-thumb {
            width: 50px;
            height: 50px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="nav">
        <div class="user-info">
            <h3><a href="admin_dashboard.php">Admin Dashboard
                </a></h3>
            <small>Logged in as:
                <?php echo $_SESSION['clerk_user']; ?>
            </small>
        </div>
        <div class="nav-links">
            <a href="add_student.php"
                style="background: var(--success); color: white; padding: 10px 18px; text-decoration: none; border-radius: 6px; font-weight: 600;">+
                Register Student</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="search-section">
            <form class="search-form" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search across all institutions..."
                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="btn-search">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Inst.</th>
                    <th>Unique ID</th>
                    <th>Name</th>
                    <th>Roll No</th>
                    <th>QR</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');

                // Super Admin query: pulls from ALL institutions[cite: 2, 8]
                $query = "SELECT * FROM students";
                if (!empty($search)) {
                    $query .= " WHERE (name LIKE '%$search%' OR roll_no LIKE '%$search%' OR unique_id LIKE '%$search%' OR institution LIKE '%$search%')";
                }
                $query .= " ORDER BY institution ASC, unique_id DESC";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $encoded_id = urlencode($row['unique_id']);
                        $qr_path = $row['qr_path']; // Using local QR path[cite: 2]
                
                        echo "<tr>
                            <td><span class='inst-badge'>{$row['institution']}</span></td>
                            <td><strong>{$row['unique_id']}</strong></td>
                            <td>{$row['name']}</td>
                            <td>{$row['roll_no']}</td>
                            <td><img src='{$qr_path}' class='qr-thumb' alt='QR'></td>
                            <td>
                                <a href='view_profile.php?id={$encoded_id}' class='view-link'>View Details</a>
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