<?php
$conn = mysqli_connect("localhost", "root", "", "gate_pass");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start();
?>