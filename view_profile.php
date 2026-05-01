<?php
include('db_config.php');


// Now handle the student data fetching
if (isset($_GET['id'])) {
    $u_id = mysqli_real_escape_string($conn, $_GET['id']);

    // We remove the institution check from the SQL because the 
    // gate guard session doesn't have an 'institution' variable assigned yet.
    $sql = "SELECT * FROM students WHERE unique_id='$u_id'";
    $res = mysqli_query($conn, $sql);
    $s = mysqli_fetch_assoc($res);

    if (!$s) {
        die("Student record not found.");
    }
} else {
    die("No ID provided.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>ID Pass -
        <?php echo $s['name']; ?>
    </title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-card {
            width: 450px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            object-fit: cover;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
        }

        .header span {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .detail-item {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 4px;
        }

        .detail-item label {
            font-size: 10px;
            color: #7f8c8d;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .detail-item span {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            display: block;
        }

        .qr-section {
            grid-column: span 2;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-top: 5px;
        }

        .qr-section p {
            font-size: 11px;
            margin: 0 0 5px 0;
            color: #2c3e50;
            font-weight: bold;
        }

        .qr-section img {
            width: 80px;
            height: 80px;
        }

        .signature-box {
            grid-column: span 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 0;
        }

        .signature-img {
            height: 35px;
            max-width: 120px;
            object-fit: contain;
        }

        .footer-action {
            text-align: center;
            padding-bottom: 15px;
        }

        .btn-print {
            background: #3498db;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }

        @media print {
            .btn-print {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .profile-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>
    <div class="profile-card">
        <div class="header">
            <img src="uploads/photos/<?php echo $s['photo']; ?>" alt="Student Photo">
            <h2>
                <?php echo $s['name']; ?>
            </h2>
            <span>ID:
                <?php echo $s['unique_id']; ?>
            </span>
        </div>

        <div class="content">
            <div class="detail-item"><label>Roll Number</label><span>
                    <?php echo $s['roll_no']; ?>
                </span></div>
            <div class="detail-item"><label>Institution</label><span>
                    <?php echo $s['institution']; ?>
                </span></div>
            <div class="detail-item"><label>Course</label><span>
                    <?php echo $s['course']; ?>
                </span></div>
            <div class="detail-item"><label>Phone No</label><span>
                    <?php echo $s['phone_no']; ?>
                </span></div>
            <div class="detail-item"><label>Gate No</label><span>
                    <?php echo $s['gate_no']; ?>
                </span></div>
            <div class="detail-item">
                <label>Academic Period</label>
                <span>
                    <?php
                    // Updated keys to match database exactly
                    $start = $s['admission_year'];
                    $end = $s['passing_year'];

                    if (!empty($start) && $start > 0) {
                        echo htmlspecialchars($start) . " - " . htmlspecialchars($end);
                    } else {
                        echo "YYYY - YYYY";
                    }
                    ?>
                </span>
            </div>

            <div class="signature-box">
                <div class="detail-item" style="border:none;"><label>Signature</label>
                    <img src="uploads/signatures/<?php echo $s['signature']; ?>" class="signature-img">
                </div>
                <div class="qr-section" style="width: 100px; margin:0; padding:5px;">
                    <img src="<?php echo $s['qr_path']; ?>">
                </div>
            </div>
        </div>

        <div class="footer-action">
            <button onclick="window.print()" class="btn-print">Print Student Pass</button>
        </div>
    </div>
</body>

</html>