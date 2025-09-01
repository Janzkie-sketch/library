<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "user") {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>User Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="text-center">👤 User Dashboard</h2>
  <div class="d-flex justify-content-center mt-4 gap-3">
    <a href="borrow.php" class="btn btn-primary">View & Borrow Books</a>
    <a href="return.php" class="btn btn-success">Return Books</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
</div>
</body>
</html>
