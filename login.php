<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        // Verify hashed password
        if (password_verify($password, $row['password'])) {

            // Store session
            $_SESSION['user'] = $row['username'];
            $_SESSION['department'] = $row['department']; // IMPORTANT

            header("Location: dashboard.php");
            exit();

        } else {
            echo "Invalid password";
        }

    } else {
        echo "User not found";
    }
}

?>

<link rel="stylesheet" href="style.css">

<div class="container">
    <form method="POST">
        <h2>Admin Login</h2>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>
</div>