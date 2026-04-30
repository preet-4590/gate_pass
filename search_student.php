<?php
session_start();
include 'db.php';

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
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM students WHERE UniqueID=$id");
    $data = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Student</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #00d2ff;
            display: flex;
            justify-content: center;
            padding: 50px 20px;
            margin: 0;
        }

        .main-card {
            display: flex;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1000px;
            min-height: 550px;
            gap: 40px;
        }

        .search-column {
            flex: 1;
            border-right: 1px solid #eee;
            padding-right: 20px;
            text-align: center;
        }

        .details-column {
            flex: 1;
        }

        /* Narrower & Centered Containers */
        .centered-wrapper {
            max-width: 300px;
            /* Adjust this to make it even smaller/larger */
            margin: 0 auto;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .btn-search {
            width: 100%;
            padding: 12px;
            background-color: #5cb8ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        /* QR Scanner Area - Narrowed and Centered */
        .qr-section {
            margin-top: 40px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }

        #reader {
            width: 100% !important;
            /* Forces the internal scanner to fit the wrapper */
        }

        /* Details Styling */
        .student-images {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }

        .student-images img {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border: 1px solid #ddd;
        }

        .placeholder {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-style: italic;
        }

        @media (max-width: 850px) {
            .main-card {
                flex-direction: column;
            }

            .search-column {
                border-right: none;
                border-bottom: 1px solid #eee;
                padding-bottom: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="main-card">

        <!-- Left Column -->
        <div class="search-column">
            <h2>Search Student</h2>

            <!-- Centered Search Form -->
            <div class="centered-wrapper">
                <form method="POST">
                    <input type="text" name="roll" placeholder="Enter Roll No" required>
                    <button name="search" class="btn-search">Search</button>
                </form>

                <!-- Centered QR Section -->
                <div class="qr-section">
                    <h3 style="margin-top:0;">Scan QR Code</h3>
                    <div id="reader"></div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="details-column">
            <?php if ($data): ?>
                <div class="details-content">
                    <h3 style="border-bottom: 2px solid #5cb8ff; padding-bottom: 10px;">Student Details</h3>
                    <p><b>ID:</b>
                        <?php echo htmlspecialchars($data['UniqueID']); ?>
                    </p>
                    <p><b>Name:</b>
                        <?php echo htmlspecialchars($data['Name']); ?>
                    </p>
                    <p><b>Roll No:</b>
                        <?php echo htmlspecialchars($data['RollNo']); ?>
                    </p>
                    <p><b>Course:</b>
                        <?php echo htmlspecialchars($data['Course']); ?>
                    </p>
                    <p><b>Phone:</b>
                        <?php echo htmlspecialchars($data['PhoneNo']); ?>
                    </p>

                    <div class="student-images">
                        <img src="uploads/photos/<?php echo $data['Photo']; ?>" alt="Photo">
                        <img src="uploads/signatures/<?php echo $data['Signature']; ?>" alt="Signature">
                    </div>

                    <div class="qr-output">
                        <p><b>Student QR:</b></p>
                        <img src="uploads/qr/<?php echo $data['QR_Code']; ?>" width="100">
                    </div>
                </div>
            <?php else: ?>
                <div class="placeholder">
                    <p>Enter a Roll Number or Scan QR to view details.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText) {
            let id = decodedText.replace("ID: ", "").trim();
            window.location.href = "search_student.php?id=" + id;
        }

        // Adjusted qrbox size to fit the narrower container
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 180 }
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>

</html>
