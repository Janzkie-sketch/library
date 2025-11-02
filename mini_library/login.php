<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and check credentials
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Save session
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
     if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit;

    } elseif ($user['role'] === 'librarian') {
        header("Location: user_dashboard.php");
        exit;
        }
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>
