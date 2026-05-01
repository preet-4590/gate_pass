<?php include('db_config.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Clerk Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #2980b9;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>Clerk Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $res = mysqli_query($conn, "SELECT * FROM clerks WHERE username='$user' AND password='$pass'");
        if ($row = mysqli_fetch_assoc($res)) {
            $_SESSION['clerk_user'] = $row['username'];
            $_SESSION['clerk_inst'] = $row['institution'];
            header("Location: dashboard.php");
        } else {
            echo "<script>alert('Invalid Credentials');</script>";
        }
    }
    ?>
</body>

</html>