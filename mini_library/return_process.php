<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return_book'])) {
    $record_id = $_POST['record_id'];
    $book_id = $_POST['book_id'];
    $copies_borrowed = $_POST['copies_borrowed'];

    if (!is_numeric($record_id) || !is_numeric($book_id) || !is_numeric($copies_borrowed)) {
        $_SESSION['message'] = "Invalid input detected.";
        $_SESSION['message_type'] = "error";
        header("Location: return_books.php");
        exit();
    }

    // ✅ Start transaction for safety
    $conn->begin_transaction();

    try {
        // Step 1: Add back the returned copies
        $updateBook = $conn->prepare("UPDATE books SET available_copies = available_copies + ? WHERE id = ?");
        $updateBook->bind_param("ii", $copies_borrowed, $book_id);
        $updateBook->execute();
        $updateBook->close();

        // Step 2: Record the actual return date
        $today = date("Y-m-d");
        $updateRecord = $conn->prepare("UPDATE borrow_records SET actual_return_date = ? WHERE id = ?");
        $updateRecord->bind_param("si", $today, $record_id);
        $updateRecord->execute();
        $updateRecord->close();

        // ✅ Commit both updates
        $conn->commit();

        // ✅ SweetAlert message setup
        $_SESSION['message'] = "Book returned successfully!";
        $_SESSION['message_type'] = "success";

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = "Error returning book: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    // ✅ Redirect back to return_books.php
    header("Location: return_books.php");
    exit();
}
?>
