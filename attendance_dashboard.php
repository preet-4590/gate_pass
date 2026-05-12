<?php
include('db_config.php');

// 1. Get the Search ID from the URL (Passed from search_portal.php)
$search_id = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if (!$search_id) {
    die("Please select a student from the Search Portal to view logs.");
}

// 2. Fetch Student Name and Institution for the Header
$student_info = mysqli_query($conn, "SELECT name, institution FROM students WHERE unique_id = '$search_id'");
$student = mysqli_fetch_assoc($student_info);

// 3. Fetch All Logs for this particular student
$log_query = "SELECT direction, log_time FROM student_attendance 
              WHERE student_id = '$search_id' 
              ORDER BY log_time DESC";
$logs = mysqli_query($conn, $log_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance History</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        .log-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .header {
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h2 {
            margin: 0;
            color: #2c3e50;
        }

        .header p {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 1.1em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #34495e;
            color: white;
            text-align: left;
            padding: 15px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #34495e;
        }

        .status-pill {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85em;
            text-transform: uppercase;
        }

        .status-IN {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-OUT {
            background: #ffebee;
            color: #c62828;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>

    <div class="log-container">

        <div class="header">
            <h2>
                <?php echo htmlspecialchars($student['name'] ?? 'Unknown Student'); ?>
            </h2>
            <p>
                <?php echo htmlspecialchars($student['institution'] ?? ''); ?> | ID: <b>
                    <?php echo htmlspecialchars($search_id); ?>
                </b>
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Movement Status</th>
                    <th>Date & Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($logs) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($logs)): ?>
                        <tr>
                            <td>
                                <span class="status-pill status-<?php echo $row['direction']; ?>">
                                    <?php echo $row['direction']; ?>
                                </span>
                            </td>
                            <td>
                                <b>
                                    <?php echo date('d M Y', strtotime($row['log_time'])); ?>
                                </b>
                                <span style="color: #95a5a6; margin-left: 10px;">
                                    <?php echo date('h:i A', strtotime($row['log_time'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="text-align: center; padding: 30px; color: #95a5a6;">
                            No activity recorded yet for this student.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>