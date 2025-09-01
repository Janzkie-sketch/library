<?php
$host = "localhost";
$user = "root";  // default in XAMPP
$pass = "";
$db   = "library_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
