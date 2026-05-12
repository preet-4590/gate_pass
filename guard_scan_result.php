<?php
// Include your existing config for $conn
include('db_config.php');

// 1. Check if ID is provided in the URL
if (!isset($_GET['id'])) {
    die("Error: No Student ID provided. Please scan the QR code again.");
}

// 2. Clean the ID from the URL
// This matches the 'unique_id' column in your database (e.g., #E1)
$student_id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. FETCH PROFILE - Changed 'id' to 'unique_id' to match your SQL structure
$student_query = mysqli_query($conn, "SELECT name, institution FROM students WHERE unique_id = '$student_id'");
$student = mysqli_fetch_assoc($student_query);

if ($student) {
    $name = $student['name'];
    $inst = $student['institution'];

    // 4. LOGIC TO TOGGLE DIRECTION
    // We check the 'student_attendance' table for the last movement of this unique_id
    $last_log_query = "SELECT direction FROM student_attendance 
                       WHERE student_id = '$student_id' 
                       ORDER BY log_time DESC LIMIT 1";

    $last_log_res = mysqli_query($conn, $last_log_query);
    $last_log = mysqli_fetch_assoc($last_log_res);

    // Toggle: If no record exists or last was OUT, they are now coming IN
    $direction = (!$last_log || $last_log['direction'] == 'OUT') ? 'IN' : 'OUT';

    // 5. SAVE THE RECORD
    $insert_sql = "INSERT INTO student_attendance (student_id, student_name, institution, direction, log_time) 
                   VALUES ('$student_id', '$name', '$inst', '$direction', NOW())";

    if (!mysqli_query($conn, $insert_sql)) {
        die("Database Error: " . mysqli_error($conn));
    }
} else {
    // This happens if the QR code ID doesn't exist in the 'students' table
    die("Access Denied: Student ID ($student_id) not found in records.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Result</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .status-header {
            padding: 40px 20px;
            font-size: 2.5em;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        .IN {
            background: #28a745;
        }

        /* Green for Entry */
        .OUT {
            background: #dc3545;
        }

        /* Red for Exit */
        .details {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .history {
            padding: 20px;
        }

        table {
            width: 90%;
            margin: 10px auto;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .btn-scan {
            display: inline-block;
            margin: 30px;
            padding: 20px 40px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="status-header <?php echo $direction; ?>">
        STUDENT
        <?php echo $direction; ?>
    </div>

    <div class="details">
        <h1 style="margin:0;">
            <?php echo htmlspecialchars($name); ?>
        </h1>
        <p style="font-size: 1.2em; color: #555;">
            <strong>
                <?php echo htmlspecialchars($inst); ?>
            </strong><br>
            ID:
            <?php echo htmlspecialchars($student_id); ?>
        </p>
    </div>

    <div class="history">
        <h3>Recent Activity</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Use the $student_id variable we sanitized at the top of the script
                $log_query = "SELECT direction, log_time FROM student_attendance 
                  WHERE student_id = '$student_id' 
                  ORDER BY log_time DESC LIMIT 5";

                $logs = mysqli_query($conn, $log_query);

                if (mysqli_num_rows($logs) > 0) {
                    while ($row = mysqli_fetch_assoc($logs)) {
                        // Check if it's 'IN' or 'OUT' to add a little color coding (optional)
                        $color = ($row['direction'] == 'IN') ? 'green' : 'red';
                        echo "<tr>
                    <td style='color:$color; font-weight:bold;'>{$row['direction']}</td>
                    <td>" . date('d M, Y | h:i A', strtotime($row['log_time'])) . "</td>
                  </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No previous logs found for this student.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <a href="guard_scanner.php" class="btn-scan">SCAN NEXT STUDENT</a>

</body>

</html>