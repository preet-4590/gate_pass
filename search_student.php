<?php
session_start();
include 'db.php';

$dept = $_SESSION['department'];

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$data = null;

if (isset($_POST['search'])) {
    $roll = $_POST['roll'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE RollNo=?");
    $stmt->bind_param("s", $roll);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $result = $conn->query("SELECT * FROM students WHERE UniqueID=$id");
    $data = $result->fetch_assoc();
}
?>

<link rel="stylesheet" href="style.css">

<div class="container">

    <h2>Search Student</h2>

    <!-- Search by Roll No -->
    <form method="POST">
        <input type="text" name="roll" placeholder="Enter Roll No" required>
        <button name="search">Search</button>
    </form>

    <br>

    <!-- QR Scanner -->
    <h3>Scan QR Code</h3>
    <div id="reader" style="width:300px;"></div>

    <?php if ($data): ?>
        <hr>
        <h3>Student Details</h3>
        <p><b>ID:</b>
            <?php echo $data['UniqueID']; ?>
        </p>
        <p><b>Name:</b>
            <?php echo $data['Name']; ?>
        </p>
        <p><b>Roll No:</b>
            <?php echo $data['RollNo']; ?>
        </p>
        <p><b>Course:</b>
            <?php echo $data['Course']; ?>
        </p>
        <p><b>Phone:</b>
            <?php echo $data['PhoneNo']; ?>
        </p>

        <img src="uploads/photos/<?php echo $data['Photo']; ?>" style="height:120px; width:120px;">
        <img src="uploads/signatures/<?php echo $data['Signature']; ?>" style="height:120px; width:120px;">

        <br><br>
        <img src="uploads/qr/<?php echo $data['QR_Code']; ?>" width="120">
    <?php endif; ?>

</div>

<!-- QR Scanner Script -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    function onScanSuccess(decodedText) {
        // Extract ID from QR (assuming QR = "ID: 5")
        let id = decodedText.replace("ID: ", "");
        window.location.href = "search_student.php?id=" + id;
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 200 }
    );
    html5QrcodeScanner.render(onScanSuccess);
</script>