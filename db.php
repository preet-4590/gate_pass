<?php
$servername = "localhost";
$username = "root";
$password = ""; // default in XAMPP
$database = "entry_pass";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>