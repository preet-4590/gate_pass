<?php
session_start();
include 'db.php';
include "phpqrcode/qrlib.php";

$dept = $_SESSION['department'];

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $roll = $_POST['roll'];
    $name = $_POST['name'];
    $course = $_POST['course'];
    $phone = $_POST['phone'];
    $gate = $_POST['gate'];
    $admission = $_POST['admission'];
    $passing = $_POST['passing'];


    $photo = time() . "_" . $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/photos/" . $photo);

    $signature = time() . "_" . $_FILES['signature']['name'];
    move_uploaded_file($_FILES['signature']['tmp_name'], "uploads/signatures/" . $signature);

    $sql = "INSERT INTO students 
    (RollNo, Name, Course, PhoneNo, GateNo, Year_of_admission, Passing_year, Photo, Signature)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssiiss", $roll, $name, $course, $phone, $gate, $admission, $passing, $photo, $signature);

    $message = "";

    if ($stmt->execute()) {

        // Get auto-generated UniqueID
        $student_id = $conn->insert_id;

        // Data to encode in QR
        $qr_text = "ID: " . $student_id;

        // File name
        $qr_file = "qr_" . $student_id . ".png";
        $qr_path = "uploads/qr/" . $qr_file;

        // Generate QR
        QRcode::png($qr_text, $qr_path, QR_ECLEVEL_L, 5);

        // Save QR path in database (optional)
        $conn->query("UPDATE students SET QR_Code='$qr_file' WHERE UniqueID=$student_id");

        $message = "<div class='success'>✔ Student Added Successfully</div>";
    }
}
?>

<?php if (!empty($message))
    echo $message; ?>

<link rel="stylesheet" href="style.css">

<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <h2>Add Student</h2>

        <label>Roll No</label>
        <input type="text" name="roll" required>

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Course</label>
        <input type="text" name="course" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Gate No</label>
        <input type="text" name="gate">

        <label>Admission Year</label>
        <input type="number" name="admission">

        <label>Passing Year</label>
        <input type="number" name="passing">

        <label>Photo</label>
        <input type="file" name="photo">

        <label>Signature</label>
        <input type="file" name="signature">

        <button type="submit">Save</button>
    </form>
</div>

<script>
    setTimeout(() => {
        let msg = document.querySelector('.success, .error');
        if (msg) msg.style.display = 'none';
    }, 3000);
</script>