<?php include('db_config.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Gate Control</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        #reader {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 2px solid #34495e;
        }

        .divider {
            margin: 25px 0;
            border-bottom: 1px solid #ddd;
            position: relative;
        }

        .divider span {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 15px;
            color: #888;
            font-weight: bold;
        }

        .manual-form {
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Gate Control Panel</h2>

        <div id="reader"></div>
        <p style="color: #666;">Scan Student QR Code</p>

        <div class="divider"><span>OR MANUAL ENTRY</span></div>

        <form action="guard_scan_result.php" method="GET" class="manual-form">
            <label>Gate Number</label>
            <select name="gate_no" required>
                <option value="Gate 1">Gate 1</option>
                <option value="Gate 2">Gate 2</option>
                <option value="Main Gate">Main Gate</option>
            </select>

            <label>Unique ID (e.g. #E6)</label>
            <input type="text" name="id" placeholder="Enter Unique ID" required>

            <button type="submit" class="btn-submit">Log Entry / Exit</button>
        </form>
    </div>

    <script>
        function onScanSuccess(decodedText) {
            let studentId = decodedText.includes('id=') ? decodedText.split('id=').pop() : decodedText;
            // Automatically assume Gate 1 if scanned via camera, or change as needed
            window.location.href = "guard_scan_result.php?id=" + encodeURIComponent(studentId) + "&gate_no=Gate 1";
        }
        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>

</body>

</html>