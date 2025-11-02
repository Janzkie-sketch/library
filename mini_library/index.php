<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Secure query: only check username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
                exit;
            } else {
                header("Location: user_dashboard.php");
                exit;
            }
        } else {
            $error = "❌ Invalid username or password.";
        }
    } else {
        $error = "❌ Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Library Login</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

    <!-- Logo at the very top -->
    <div class="top-logo">
        <img src="img/logo.png" alt="Library Logo">      
    </div>

    <!-- Main container -->
    <div class="container">
        <!-- Left Side -->
        <div class="left-side">
            <h1>Welcome to Mini Library</h1>
            <p>
                Explore endless books, borrow with ease, and enjoy reading anytime.
                Discover new stories, gain knowledge, and make learning fun.
                Your next adventure starts here.
                <br>
                <br>
                Log in now and begin!
            </p>
        </div>

        <!-- Right Side -->
        <div class="right-side">
            <div class="login-card">
                <h2>Login to Continue</h2>

                <!-- 🔴 Error Message Display -->
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required placeholder="Enter username">
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="Enter password">
                    </div>             
                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
