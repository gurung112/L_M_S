<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include the database connection

// Get the book ID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch the current data for the book
    $sql = "SELECT b.book_id, b.title, b.year, b.status, b.category_id, br.a_id, c.c_name AS category, a.a_name AS author
            FROM books b
            INNER JOIN categories c ON b.category_id = c.c_id
            LEFT JOIN book_requests br ON b.book_id = br.book_id
            INNER JOIN author a ON br.a_id = a.a_id
            WHERE b.book_id = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "Book not found.";
        exit;
    }
} else {
    echo "Invalid book ID.";
    exit;
}

// Fetch authors and categories for dropdowns
$authors_sql = "SELECT * FROM author";
$authors_result = $mysqli->query($authors_sql);

$categories_sql = "SELECT * FROM categories";
$categories_result = $mysqli->query($categories_sql);

// Handle form submission to update the book
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $author_id = $_POST['author_id'];
    $year = $_POST['year'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];

    // Validate if all fields are filled
    if (!empty($title) && !empty($author_id) && !empty($year) && !empty($category_id) && !empty($status)) {
        // Update the book details in the database
        $update_sql = "UPDATE books 
                       SET title = ?, year = ?, category_id = ?, status = ? 
                       WHERE book_id = ?";
        $stmt_update = $mysqli->prepare($update_sql);
        $stmt_update->bind_param('siisi', $title, $year, $category_id, $status, $book_id);

        if ($stmt_update->execute()) {
            // Optionally update the book-request table if needed
            $update_request_sql = "UPDATE book_requests SET a_id = ? WHERE book_id = ?";
            $stmt_request = $mysqli->prepare($update_request_sql);
            $stmt_request->bind_param('ii', $author_id, $book_id);
            $stmt_request->execute();

            header('Location: admin_library.php');  // Redirect back to the library page
            exit;
        } else {
            $error_message = "Error updating book. Please try again.";
        }
    } else {
        // Display error message if any field is empty
        $error_message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="abc.css">
    <!-- <link rel="stylesheet" href="edit_author.css"> -->
    <!-- <link rel="stylesheet" href="edit_category.css"> -->
    <link rel="stylesheet" href="edit_book.css">
</head>
<body>
    <div class="heading">
        <h1>PEACE</h1>
        <a href="logout.php">Logout</a>
    </div>
    <div class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_library.php">Library</a>
        <a href="admin_categories.php">Categories</a>
        <a href="admin_author.php">Author</a>
        <a href="admin_bookrequest.php">Book Requests</a>
        <a href="admin_usermanagement.php">User Management</a>
    </div>
    <div class="content_f">
        <h1>Edit Book</h1>

        <?php if (isset($error_message)) : ?>
            <p style="color:red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Form to edit a book -->
        <form method="POST" action="edit_book.php?id=<?php echo $book_id; ?>">
            <label for="title">Book Title:</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            
            <label for="author_id">Author:</label>
            <select name="author_id" id="author_id" required>
                <option value="">Select Author</option>
                <?php while ($author = $authors_result->fetch_assoc()) : ?>
                    <option value="<?php echo $author['a_id']; ?>" <?php echo ($author['a_id'] == $book['a_id']) ? 'selected' : ''; ?>>
                        <?php echo $author['a_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label for="year">Year:</label>
            <input type="number" name="year" id="year" value="<?php echo $book['year']; ?>" required>
            
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <option value="">Select Category</option>
                <?php while ($category = $categories_result->fetch_assoc()) : ?>
                    <option value="<?php echo $category['c_id']; ?>" <?php echo ($category['c_id'] == $book['category_id']) ? 'selected' : ''; ?>>
                        <?php echo $category['c_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Available" <?php echo ($book['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                <option value="Checked Out" <?php echo ($book['status'] == 'Checked Out') ? 'selected' : ''; ?>>Checked Out</option>
                <option value="Reserved" <?php echo ($book['status'] == 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
            </select>

            <button type="submit">Update Book</button>
        </form>
    </div>
</body>
</html>

<?php
$mysqli->close();  // Close the database connection
?>
