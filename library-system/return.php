<?php
session_start();
include "db.php";
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "user") {
    header("Location: index.php");
    exit();
}

// Return Book
if (isset($_GET['return'])) {
    $record_id = $_GET['return'];
    $date = date("Y-m-d H:i:s");

    // Update return date in borrow_records
    $conn->query("UPDATE borrow_records SET return_date='$date' WHERE id=$record_id");

    // Get book_id of that record
    $book = $conn->query("SELECT book_id FROM borrow_records WHERE id=$record_id")->fetch_assoc();
    $book_id = $book['book_id'];

    // Set book back to Available
    $conn->query("UPDATE books SET status='Available' WHERE id=$book_id");

    header("Location: return.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Return Books</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">🔙 Borrowed Books</h2>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Book Title</th>
        <th>Author</th>
        <th>Borrow Date</th>
        <th>Return Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT br.*, b.title, b.author 
              FROM borrow_records br
              JOIN books b ON br.book_id = b.id
              WHERE br.return_date IS NULL";
      $result = $conn->query($sql);

      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['title']}</td>
                  <td>{$row['author']}</td>
                  <td>{$row['borrow_date']}</td>
                  <td>" . ($row['return_date'] ? $row['return_date'] : "<span class='text-danger'>Not Returned</span>") . "</td>
                  <td>
                    <a href='return.php?return={$row['id']}' class='btn btn-success btn-sm'>Return</a>
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
