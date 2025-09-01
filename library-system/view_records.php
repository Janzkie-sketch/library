<?php
session_start();
include "db.php";
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Borrow Records</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">📖 Borrow Records</h2>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Book Title</th>
        <th>Author</th>
        <th>Borrower Name</th>
        <th>Course</th>
        <th>Borrow Date</th>
        <th>Return Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT br.*, b.title, b.author 
              FROM borrow_records br
              JOIN books b ON br.book_id = b.id";
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['title']}</td>
                  <td>{$row['author']}</td>
                  <td>{$row['borrower_name']}</td>
                  <td>{$row['borrower_course']}</td>
                  <td>{$row['borrow_date']}</td>
                  <td>" . ($row['return_date'] ? $row['return_date'] : "<span class='text-danger'>Not Returned</span>") . "</td>
                </tr>";
      }
      ?>
    </tbody>
  </table>

  <a href="admin_dashboard.php" class="btn btn-secondary mt-3">⬅ Back</a>
</div>
</body>
</html>
