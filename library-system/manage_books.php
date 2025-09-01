<?php
session_start();
include "db.php";
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: index.php");
    exit();
}

// ADD BOOK
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $sql = "INSERT INTO books (title, author, status) VALUES ('$title', '$author', 'Available')";
    $conn->query($sql);
    header("Location: manage_books.php");
    exit();
}

// DELETE BOOK
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM books WHERE id=$id");
    header("Location: manage_books.php");
    exit();
}

// GET BOOK DATA FOR EDIT
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();
}

// UPDATE BOOK
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $conn->query("UPDATE books SET title='$title', author='$author' WHERE id=$id");
    header("Location: manage_books.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Books</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4"> MANAGE BOOKS</h2>

  <!-- Add / Edit Book Form -->
  <form method="POST" class="mb-4">
    <div class="row">
      <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
      <div class="col-md-4">
        <input type="text" name="title" placeholder="Book Title" value="<?= $editData['title'] ?? '' ?>" class="form-control" required>
      </div>
      <div class="col-md-4">
        <input type="text" name="author" placeholder="Author" value="<?= $editData['author'] ?? '' ?>" class="form-control" required>
      </div>
      <div class="col-md-4">
        <?php if ($editData): ?>
          <button type="submit" name="update" class="btn btn-success w-100">Update Book</button>
          <a href="manage_books.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        <?php else: ?>
          <button type="submit" name="add" class="btn btn-primary w-100">Add Book</button>
        <?php endif; ?>
      </div>
    </div>
  </form>

  <!-- Books Table -->
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
      $result = $conn->query("SELECT * FROM books");
      while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['id']}</td>
                  <td>{$row['title']}</td>
                  <td>{$row['author']}</td>
                  <td>{$row['status']}</td>
                  <td>
                    <a href='manage_books.php?edit={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='manage_books.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Delete this book?');\">Delete</a>
                  </td>
                </tr>";
      }
      ?>
    </tbody>
  </table>

  <a href="admin_dashboard.php" class="btn btn-secondary mt-3"> -Back-</a>
</div>
</body>
</html>
