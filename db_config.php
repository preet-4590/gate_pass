<?php
$conn = mysqli_connect("localhost", "root", "", "gate_pass");
$site_url = "https://glandular-barcode-kitten.ngrok-free.dev";

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();
?>
