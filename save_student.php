<?php
include('db_config.php'); // This file contains your $conn and $site_url
include('phpqrcode/qrlib.php');

if (isset($_POST['save'])) {
    // 1. Capture and Clean Form Data
    $u_id = mysqli_real_escape_string($conn, $_POST['u_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $roll = mysqli_real_escape_string($conn, $_POST['roll']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $gate = mysqli_real_escape_string($conn, $_POST['gate']);
    $adm_year = mysqli_real_escape_string($conn, $_POST['adm_year']);
    $pass_year = mysqli_real_escape_string($conn, $_POST['pass_year']);
    $inst = mysqli_real_escape_string($conn, $_POST['institution']);

    // 2. Setup Paths for Uploads
    $qr_path = "uploads/qrcodes/" . str_replace('#', '', $u_id) . ".png";

    // File upload logic (Simplified for Photo and Signature)
    $photo = $_FILES['photo']['name'];
    $signature = $_FILES['signature']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/photos/" . $photo);
    move_uploaded_file($_FILES['signature']['tmp_name'], "uploads/signatures/" . $signature);

    // 3. THE FIXED SQL QUERY (No more dots!)
    $sql = "INSERT INTO students (unique_id, roll_no, name, institution, course, phone_no, gate_no, admission_year, passing_year, photo, signature, qr_path) 
            VALUES ('$u_id', '$roll', '$name', '$inst', '$course', '$phone', '$gate', '$adm_year', '$pass_year', '$photo', '$signature', '$qr_path')";

    if (mysqli_query($conn, $sql)) {
        // 4. Generate QR Code linking to your Scan Handler
        // Using $site_url from db_config.php
        $profile_url = $site_url . "/scan_handler.php?id=" . urlencode($u_id);

        // Ensure the qrcodes folder exists before saving
        if (!file_exists('uploads/qrcodes')) {
            mkdir('uploads/qrcodes', 0777, true);
        }

        QRcode::png($profile_url, $qr_path, 'L', 10, 2);

        // 5. Success Redirect
        if ($_SESSION['role'] == 'super_admin') {
            header("Location: admin_dashboard.php?success=1");
        } else {
            header("Location: dashboard.php?success=1");
        }
    } else {
        // This will help you find errors if the query fails for other reasons
        echo "Database Error: " . mysqli_error($conn);
    }
}
?>
