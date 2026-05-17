<?php
include('db_config.php');

// Retrieve search reference query target parameters safely
$search_id = isset($_GET['search']) ? mysqli_real_escape_string($conn, urldecode($_GET['search'])) : '';

if (empty($search_id)) {
    die("Direct access prohibited. Request context parameters missing.");
}

// Fetch general profile details header metrics
$profile_res = mysqli_query($conn, "SELECT name, institution, unique_id FROM students WHERE unique_id = '$search_id'");
$profile = mysqli_fetch_assoc($profile_res);

if (!$profile) {
    die("Target core matrix missing.");
}

// Fetch matching localized historic tracking collection array items
$logs = mysqli_query($conn, "SELECT direction, gate_no, log_time FROM student_attendance WHERE student_id = '$search_id' ORDER BY log_time DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking History Metrics Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 40px 20px;
        }

        .log-container {
            max-width: 850px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }

        .back-link:hover {
            color: #2980b9;
        }

        .header {
            border-bottom: 2px solid #f1f3f5;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .header h2 {
            margin: 0;
            color: #2c3e50;
            font-size: 28px;
            font-weight: 700;
        }

        .header p {
            margin: 6px 0 0;
            color: #7f8c8d;
            font-size: 15px;
            font-weight: 500;
        }

        .header b {
            color: #34495e;
        }

        h3.section-title {
            text-align: left;
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 10px;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }

        th {
            background-color: #34495e;
            color: #ffffff;
            text-align: left;
            padding: 16px 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f3f5;
            color: #495057;
            font-size: 15px;
            vertical-align: middle;
            text-align: left;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8f9fa;
        }

        .log-date {
            font-weight: 600;
            color: #2c3e50;
        }

        .log-time {
            color: #adb5bd;
            margin-left: 12px;
            font-weight: 400;
        }

        .status-pill {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            min-width: 45px;
            text-align: center;
        }

        .status-IN {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .status-OUT {
            background-color: #ffebee;
            color: #c62828;
        }

        .gate-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #f1f3f5;
            color: #495057;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #e9ecef;
        }

        .no-data {
            text-align: center;
            padding: 40px !important;
            color: #b5b5b5;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="log-container">
        <a href="dashboard.php" class="back-link">← Return to Dashboard</a>

        <div class="header">
            <h2>
                <?php echo htmlspecialchars($profile['name']); ?>
            </h2>
            <p>Institution: <b>
                    <?php echo htmlspecialchars($profile['institution']); ?>
                </b> | Student ID: <b>
                    <?php echo htmlspecialchars($profile['unique_id']); ?>
                </b></p>
        </div>

        <h3 class="section-title">Historical Log Ledger</h3>
        <table>
            <thead>
                <tr>
                    <th>Movement Status</th>
                    <th>Checkpoint Gate</th>
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
                                <span class="gate-badge">
                                    <?php echo !empty($row['gate_no']) ? htmlspecialchars($row['gate_no']) : 'N/A'; ?>
                                </span>
                            </td>

                            <td>
                                <span class="log-date">
                                    <?php echo date('d M Y', strtotime($row['log_time'])); ?>
                                </span>
                                <span class="log-time">
                                    <?php echo date('h:i A', strtotime($row['log_time'])); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="no-data">No gate entry or exit logs recorded yet for this profile.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
