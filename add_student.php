<?php
include('db_config.php');
include('phpqrcode/qrlib.php');

if (!isset($_SESSION['clerk_user'])) {
    header("Location: login.php");
    exit;
}

$inst = $_SESSION['clerk_inst'];
// Update this URL to your current Ngrok address for mobile scanning[cite: 1, 2]
$site_url = "https://glandular-barcode-kitten.ngrok-free.dev";

if (isset($_POST['save'])) {
    // 1. Generate Unique ID Logic (GNDEC=#E, GNDPC=#P, GNDITI=#I)[cite: 1]
    $map = ['GNDEC' => '#E', 'GNDPC' => '#P', 'GNDITI' => '#I'];
    $prefix = $map[$inst];
    $count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM students WHERE institution='$inst'");
    $count = mysqli_fetch_assoc($count_res)['total'] + 1;
    $u_id = $prefix . $count;


    // Update this to include your actual subfolder name
    $profile_url = $site_url . "/gate_pass/view_profile.php?id=" . urlencode($u_id);

    // This part is for the file storage path on your PC (leave as is)
    $qr_path = "uploads/qrcodes/" . str_replace('#', '', $u_id) . ".png";

    if (!file_exists('uploads/qrcodes')) {
        mkdir('uploads/qrcodes', 0777, true);
    }
    QRcode::png($profile_url, $qr_path, 'H', 4, 2);

    // 3. Handle File Uploads[cite: 1]
    $photo_name = time() . "_photo_" . $_FILES['photo']['name'];
    $sig_name = time() . "_sig_" . $_FILES['signature']['name'];

    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/photos/" . $photo_name);
    move_uploaded_file($_FILES['signature']['tmp_name'], "uploads/signatures/" . $sig_name);

    // 4. Collect Form Data with Corrected Keys[cite: 1, 5]
    $roll = mysqli_real_escape_string($conn, $_POST['roll']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gate = mysqli_real_escape_string($conn, $_POST['gate']);
    $adm_year = mysqli_real_escape_string($conn, $_POST['adm_year']);
    $pass_year = mysqli_real_escape_string($conn, $_POST['pass_year']);

    // 5. Insert into DB (Aligned with your gate_pass.sql schema)[cite: 5]
    $sql = "INSERT INTO students (unique_id, roll_no, name, institution, course, phone_no, gate_no, admission_year, passing_year, photo, signature, qr_path) 
            VALUES ('$u_id', '$roll', '$name', '$inst', '$course', '$phone', '$gate', '$adm_year', '$pass_year', '$photo_name', '$sig_name', '$qr_path')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Student Registered Successfully! ID: $u_id'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Student Details</title>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
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

        h2 {
            color: var(--primary);
            border-bottom: 2px solid var(--accent);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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
        }

        .full-width {
            grid-column: span 2;
        }

        .btn-submit {
            background: var(--accent);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            width: 100%;
        }

        .btn-submit:hover {
            background: #2980b9;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Register Student -
            <?php echo htmlspecialchars($inst); ?>
        </h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="grid">
                <div class="full-width">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="Enter Full Name" required>
                </div>
                <div>
                    <label>Roll Number</label>
                    <input type="text" name="roll" placeholder="Roll No" required>
                </div>
                <div>
                    <label>Course</label>
                    <input type="text" name="course" placeholder="e.g. MCA, B.Tech" required>
                </div>
                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="10-digit number" required>
                </div>
                <div>
                    <label>Gate Number</label>
                    <input type="text" name="gate" placeholder="e.g. Gate 1" required>
                </div>

                <!-- Fix: Match names 'adm_year' and 'pass_year' to PHP logic above[cite: 1] -->
                <div>
                    <label>Admission Year</label>
                    <select name="adm_year" required>
                        <option value="">Select Year</option>
                        <?php
                        for ($year = 2021; $year <= 2031; $year++) {
                            echo "<option value='$year'>$year</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label>Passing Year</label>
                    <select name="pass_year" required>
                        <option value="">Select Year</option>
                        <?php
                        for ($year = 2021; $year <= 2031; $year++) {
                            echo "<option value='$year'>$year</option>";
                        }
                        ?>
                    </select>
                </div>

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
</body>

</html>