<?php
session_start();
include "db.php";
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "user") {
    header("Location: index.php");
    exit();
}

// Borrow Book
if (isset($_POST['borrow'])) {
    $book_id = $_POST['book_id'];
    $name = $_POST['borrower_name'];
    $course = $_POST['borrower_course'];
    $date = date("Y-m-d H:i:s");

    $conn->query("INSERT INTO borrow_records (book_id, borrower_name, borrower_course, borrow_date) 
                  VALUES ('$book_id', '$name', '$course', '$date')");
    $conn->query("UPDATE books SET status='Borrowed' WHERE id=$book_id");
    header("Location: borrow.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Borrow Books</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">📚 Available Books</h2>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT * FROM books WHERE status='Available'");
      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id']}</td>
                  <td>{$row['title']}</td>
                  <td>{$row['author']}</td>
                  <td>{$row['status']}</td>
                  <td>
                    <!-- Borrow Form -->
                    <form method='POST' class='d-flex gap-2'>
                      <input type='hidden' name='book_id' value='{$row['id']}'>
                      <input type='text' name='borrower_name' placeholder='Your Name' class='form-control' required>
                      <input type='text' name='borrower_course' placeholder='Your Course' class='form-control' required>
                      <button type='submit' name='borrow' class='btn btn-primary btn-sm'>Borrow</button>
                    </form>
                  </td>
                </tr>";
      }
      ?>
    </tbody>
  </table>

  <a href="user_dashboard.php" class="btn btn-secondary mt-3">⬅ Back</a>
</div>
</body>
</html>
