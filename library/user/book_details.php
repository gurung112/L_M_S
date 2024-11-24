<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

// Get the book ID from the URL
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];

    // Fetch book details from the database
    $query = "SELECT b.book_id, b.title, b.year, b.status, b.category_id, a.a_name, a.a_id, c.c_name 
              FROM books b 
            JOIN book_requests br ON b.book_id=br.book_id
            JOIN author a ON br.a_id = a.a_id
            JOIN categories c ON b.category_id = c.c_id
              WHERE b.book_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    // If no book is found
    if (!$book) {
        header('Location: user_books.php');
        exit;
    }
}

// Handle book request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $a_id = $book['a_id']; // Fetch the author ID for the request

    // Check if the user has already requested the book
    $query = "SELECT * FROM book_requests WHERE user_id = ? AND book_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If request already exists, show an error message
    if ($result->num_rows > 0) {
        $request_message = "You have already requested this book.";
    } else {
        // Insert the request into the book_requests table
        $query = "INSERT INTO book_requests (user_id, book_id, request_date, a_id) VALUES (?, ?, NOW(), ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("iii", $user_id, $book_id, $a_id);
        $stmt->execute();

        // Redirect to books page after request
        header("Location: user_books.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="css/user_home.css">
    <link rel="stylesheet" href="css/book_details.css">
</head>
<body>
    <div class="navbar">
        <h1>Library Management System</h1>
        <a href="user_home.php">Home</a>
        <a href="user_books.php">Books</a>
        <a href="user_profile.php">Profile</a>
        <a href="user_change_password.php">Change Password</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="book-details-container">
        <h1><?php echo htmlspecialchars($book['title']); ?></h1>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['a_name']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($book['c_name']); ?></p>
        <p><strong>Year:</strong> <?php echo htmlspecialchars($book['year']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($book['status']); ?></p>

        <!-- Show message if the book has already been requested -->
        <?php if (isset($request_message)): ?>
            <p style="color: red;"><?php echo $request_message; ?></p>
        <?php endif; ?>

        <!-- Book request form -->
        <form action="book_details.php?book_id=<?php echo $book['book_id']; ?>" method="POST">
            <button type="submit" <?php echo isset($request_message) ? 'disabled' : ''; ?>>Request this Book</button>
        </form>
    </div>
</body>
</html>
