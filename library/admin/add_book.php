<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include database connection

// Fetch authors and categories for the dropdowns
$authors_sql = "SELECT * FROM author";
$authors_result = $mysqli->query($authors_sql);

$categories_sql = "SELECT * FROM categories";
$categories_result = $mysqli->query($categories_sql);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging output to check if author_id is set correctly
    var_dump($_POST['author_id']);  // This will show the value of author_id

    // Get book details from POST request
    $title = trim($_POST['title']);
    $author_id = $_POST['author_id'];  // Author ID selected from dropdown
    $year = $_POST['year'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];

    // Validate if all fields are filled
    if (!empty($title) && !empty($author_id) && !empty($year) && !empty($category_id) && !empty($status)) {
        // Prepare the SQL query to insert a new book into the books table
        $sql = "INSERT INTO books (title, year, category_id, status) 
                VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('siis', $title, $year, $category_id, $status);

        // Execute the query to insert book
        if ($stmt->execute()) {
            // Get the book_id of the newly inserted book
            $book_id = $mysqli->insert_id;

            // Insert the relationship between the book and the author in the book_requests table
            $book_request_sql = "INSERT INTO book_requests (book_id, user_id, request_date) VALUES (?, ?, ?)";
            $stmt_request = $mysqli->prepare($book_request_sql);
            $stmt_request->bind_param('iis', $book_id, $author_id, date('Y-m-d H:i:s'));  // User ID (author) and Date
            
            if ($stmt_request->execute()) {
                header('Location: admin_library.php');  // Redirect to the library management page
                exit;
            } else {
                $error_message = "Error adding book-author relationship. Please try again.";
            }
        } else {
            $error_message = "Error adding book. Please try again.";
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
    <title>Add Book</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="addcategory.css">
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
    <div class="content">
        <h1>Add New Book</h1>

        <?php if (isset($error_message)) : ?>
            <p style="color:red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Form to add a new book -->
        <form method="POST" action="add_book.php">
            <label for="title">Book Title:</label>
            <input type="text" name="title" id="title" required>
            
            <label for="author_id">Author:</label>
            <select name="author_id" id="author_id" required>
                <option value="">Select Author</option>
                <?php while ($author = $authors_result->fetch_assoc()) : ?>
                    <option value="<?php echo $author['a_id']; ?>"><?php echo $author['a_name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="year">Year:</label>
            <input type="number" name="year" id="year" required>
            
            <label for="category_id">Category:</label>
            <select name="category_id" id="category_id" required>
                <option value="">Select Category</option>
                <?php while ($category = $categories_result->fetch_assoc()) : ?>
                    <option value="<?php echo $category['c_id']; ?>"><?php echo $category['c_name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="Available">Available</option>
                <option value="Checked Out">Checked Out</option>
                <option value="Reserved">Reserved</option>
            </select>

            <button type="submit">Add Book</button>
        </form>
    </div>
</body>
</html>
