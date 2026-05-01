<?php include('db_config.php');
if (!isset($_SESSION['clerk_user']))
    header("Location: login.php");
$inst = $_SESSION['clerk_inst'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Student Search Portal</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f6;
            margin: 0;
            padding: 40px;
        }

        .search-container {
            max-width: 800px;
            margin: auto;
            text-align: center;
        }

        .search-box {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            border: 2px solid #3498db;
            border-radius: 30px;
            outline: none;
            box-sizing: border-box;
        }

        .result-card {
            background: white;
            margin-top: 20px;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .view-btn {
            background: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .view-btn:hover {
            background: #2980b9;
        }
    </style>
</head>

<body>
    <div class="search-container">
        <h2>🔍 Student Record Lookup</h2>
        <p>Institution: <b>
                <?php echo $inst; ?>
            </b></p>

        <form method="GET">
            <input type="text" name="query" class="search-box" placeholder="Enter Name, Roll No, or Unique ID..."
                value="<?php echo $_GET['query'] ?? ''; ?>">
        </form>

        <?php
        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $q = mysqli_real_escape_string($conn, $_GET['query']);
            $sql = "SELECT * FROM students WHERE institution='$inst' AND (name LIKE '%$q%' OR roll_no LIKE '%$q%' OR unique_id LIKE '%$q%')";
            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<div class='result-card'>
                            <div style='text-align:left;'>
                                <b>{$row['name']}</b><br>
                                <small>ID: {$row['unique_id']} | Roll: {$row['roll_no']}</small>
                            </div>
                            <a href='view_profile.php?id={$row['unique_id']}' class='view-btn'>View Full Details</a>
                          </div>";
                }
            } else {
                echo "<p style='margin-top:20px; color:red;'>No student found in $inst records.</p>";
            }
        }
        ?>
    </div>
</body>

</html>