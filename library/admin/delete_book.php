<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include the database connection

// Check if the book ID is provided in the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];

    // Start a transaction to delete the related records and then the book
    $mysqli->begin_transaction();

    try {
        // Delete from the book_requests table first (dependent rows)
        $delete_requests_sql = "DELETE FROM book_requests WHERE book_id = ?";
        $stmt = $mysqli->prepare($delete_requests_sql);
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        $stmt->close();

        // Now delete the book from the books table
        $delete_book_sql = "DELETE FROM books WHERE book_id = ?";
        $stmt = $mysqli->prepare($delete_book_sql);
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $mysqli->commit();

        // Redirect to the library page after successful deletion
        header('Location: admin_library.php');
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if anything fails
        $mysqli->rollback();

        // Display an error message
        $error_message = "Error deleting the book: " . $e->getMessage();
    }
} else {
    // If no book ID is provided, display an error message
    $error_message = "Invalid book ID.";
}

$mysqli->close();  // Close the database connection
?>