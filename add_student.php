<?php
include('db_config.php');
include('phpqrcode/qrlib.php');

if (!isset($_SESSION['clerk_user'])) {
    header("Location: login.php");
    exit;
}

$is_admin = ($_SESSION['role'] == 'super_admin');
$default_inst = $is_admin ? "" : $_SESSION['clerk_inst'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Student Details</title>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        .form-container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width {
            grid-column: span 2;
        }

        label {
            font-weight: bold;
            color: #34495e;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .btn-submit {
            background: var(--accent);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            margin-top: 20px;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: #2980b9;
        }

        .readonly-id {
            background: #f8f9fa;
            font-weight: bold;
            color: var(--primary);
            border: 2px solid #3498db;
        }

        h2 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Register New Student</h2>
        <form action="save_student.php" method="POST" enctype="multipart/form-data">
            <div class="grid">
                <!-- Institution Selection -->
                <div class="full-width">
                    <label>Institution</label>
                    <?php if ($is_admin): ?>
                        <select name="institution" id="inst_select" required onchange="updateID(this.value)">
                            <option value="">-- Select Institution --</option>
                            <option value="GNDEC">GNDEC (Engineering)</option>
                            <option value="GNDPC">GNDPC (Polytechnic)</option>
                            <option value="GNDITI">GNDITI (ITI)</option>
                        </select>
                    <?php else: ?>
                        <input type="text" name="institution" value="<?php echo $default_inst; ?>" readonly>
                        <script> window.onload = function () { updateID('<?php echo $default_inst; ?>'); }; </script>
                    <?php endif; ?>
                </div>

                <!-- Auto-Generated Unique ID -->
                <div class="full-width">
                    <label>Unique ID (Auto-generated)</label>
                    <input type="text" name="u_id" id="unique_id" class="readonly-id" readonly
                        placeholder="Select Institution First">
                </div>

                <!-- Name Field -->
                <div class="full-width">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Enter Full Name" required>
                </div>

                <!-- Roll No and Course -->
                <div>
                    <label>Roll Number</label>
                    <input type="text" name="roll" placeholder="Roll No" required>
                </div>
                <div>
                    <label>Course</label>
                    <input type="text" name="course" placeholder="e.g. MCA, B.Tech" required>
                </div>

                <!-- Phone and Gate Number (Added here) -->
                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="10-digit number" required>
                </div>
                <div>
                    <label>Gate Number</label>
                    <input type="text" name="gate" placeholder="e.g. Gate 1" required>
                </div>

                <!-- Years -->
                <div>
                    <label>Admission Year</label>
                    <select name="adm_year" required>
                        <option value="">Select Year</option>
                        <?php for ($y = 2021; $y <= 2030; $y++)
                            echo "<option value='$y'>$y</option>"; ?>
                    </select>
                </div>
                <div>
                    <label>Passing Year</label>
                    <select name="pass_year" required>
                        <option value="">Select Year</option>
                        <?php for ($y = 2021; $y <= 2030; $y++)
                            echo "<option value='$y'>$y</option>"; ?>
                    </select>
                </div>

                <!-- Files -->
                <div>
                    <label>Student Photo</label>
                    <input type="file" name="photo" accept="image/*" required>
                </div>
                <div>
                    <label>Signature Scan</label>
                    <input type="file" name="signature" accept="image/*" required>
                </div>
            </div>
            <button type="submit" name="save" class="btn-submit">Generate Pass & Save Details</button>
        </form>
    </div>

    <script>
        function updateID(inst) {
            if (inst === "") {
                document.getElementById('unique_id').value = "";
                return;
            }
            // Fetching next ID from your helper file
            fetch('get_next_id.php?institution=' + inst)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('unique_id').value = data;
                });
        }
    </script>
</body>

</html>