<?php
include('db_config.php');

// 1. Check if ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No Student ID Scanned or Entered. <a href='guard_interface.php'>Back</a>");
}

// 2. Decode the URL text to turn '%23' back into '#'
// This fixes the "%23E1 is not registered" bug completely!
$raw_id = urldecode($_GET['id']);

// 3. Clean it up for the SQL statement to keep your database secure
$student_id = mysqli_real_escape_string($conn, $raw_id);

$gate_no = isset($_GET['gate_no']) ? mysqli_real_escape_string($conn, $_GET['gate_no']) : 'Main Gate';

// 4. Fetch details using the cleaned unique_id
$student_query = mysqli_query($conn, "SELECT name, institution FROM students WHERE unique_id = '$student_id'");
$student = mysqli_fetch_assoc($student_query);

if (!$student) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
            <h1 style='color:#e74c3c; font-size: 50px;'>❌ ACCESS DENIED</h1>
            <p style='font-size:18px;'>Student ID <b>" . htmlspecialchars($student_id) . "</b> is not registered.</p>
            <a href='guard_interface.php'>Return to Gate Control</a>
         </div>");
}

$student_name = $student['name'];
$institution = $student['institution'];

// Calculate IN/OUT toggle direction
$last_log_query = mysqli_query($conn, "SELECT direction FROM student_attendance WHERE student_id = '$student_id' ORDER BY log_time DESC LIMIT 1");
$last_log = mysqli_fetch_assoc($last_log_query);
$next_direction = ($last_log && $last_log['direction'] == 'IN') ? 'OUT' : 'IN';

// Insert tracking data including GATE NO
$insert_log = mysqli_query($conn, "INSERT INTO student_attendance (student_id, student_name, institution, direction, gate_no, log_time) 
                                   VALUES ('$student_id', '$student_name', '$institution', '$next_direction', '$gate_no', NOW())");

if (!$insert_log) {
    die("Logging Error: " . mysqli_error($conn));
}

// Fetch history
$history_query = mysqli_query($conn, "SELECT direction, gate_no, log_time FROM student_attendance WHERE student_id = '$student_id' ORDER BY log_time DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gate Action Result</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f6;
            padding: 20px;
            text-align: center;
        }

        .result-card {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .status-header {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
        }

        .status-IN {
            background: #d4edda;
            color: #155724;
        }

        .status-OUT {
            background: #f8d7da;
            color: #721c24;
        }

        .details {
            text-align: left;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 5px solid #34495e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            text-align: left;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background: #34495e;
            color: white;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 20px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <div class="result-card">
        <div class="status-header status-<?php echo $next_direction; ?>">
            ✅ ALLOWED
            <?php echo $next_direction; ?>
        </div>

        <div class="details">
            <p><b>Name:</b>
                <?php echo htmlspecialchars($student_name); ?>
            </p>
            <p><b>ID:</b>
                <?php echo htmlspecialchars($student_id); ?>
            </p>
            <p><b>Institution:</b>
                <?php echo htmlspecialchars($institution); ?>
            </p>
            <p><b>Gate Processed:</b>
                <?php echo htmlspecialchars($gate_no); ?>
            </p>
        </div>

        <h3>Recent Activity Logs</h3>
        <table>
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Gate</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($history_query)): ?>
                    <tr>
                        <td
                            style="font-weight: bold; color: <?php echo ($row['direction'] == 'IN') ? '#2e7d32' : '#c62828'; ?>;">
                            <?php echo $row['direction']; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['gate_no']); ?>
                        </td>
                        <td>
                            <?php echo date('d M Y, h:i A', strtotime($row['log_time'])); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="guard_interface.php" class="btn">Return to Gate Control</a>
    </div>

</body>

</html>
