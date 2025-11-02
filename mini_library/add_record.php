<?php
// ===============================================
// Database Connection File
// ===============================================

// Database credentials
$servername = "localhost";  // Default for XAMPP
$username   = "root";       // Default username
$password   = "";           // Leave blank (default in XAMPP)
$database   = "library_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Remove PHP notices and warnings in production
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

// ===============================================
// ✅ Notes:
// - Do NOT include login $_POST variables here.
// - Only use this file for establishing the database connection.
// - Include it in other PHP files using:  include "db.php";
// ===============================================
?>
