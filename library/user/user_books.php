    <?php
    session_start();
    include('config.php');

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: user_login.php');
        exit;
    }

    // Fetch all books from the database
    $query = "SELECT b.book_id, b.title, b.year, b.status, b.category_id, a.a_name, c.c_name 
            FROM books b 
            JOIN book_requests br ON b.book_id=br.book_id
            JOIN author a ON br.a_id = a.a_id
            JOIN categories c ON b.category_id = c.c_id";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = $result->fetch_all(MYSQLI_ASSOC);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Books</title>
        <link rel="stylesheet" href="css/user_home.css">
        <link rel="stylesheet" href="css/books.css">
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

        <div class="books-container">
            <h1>Available Books</h1>
            <div class="books-list">
                <?php foreach ($books as $book): ?>
                    <div class="book-item">
                        <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['a_name']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($book['c_name']); ?></p>
                        <p><strong>Year:</strong> <?php echo htmlspecialchars($book['year']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($book['status']); ?></p>
                        <a href="book_details.php?book_id=<?php echo $book['book_id']; ?>" class="view-details">View Details</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        // include ('footer.php');
        ?>
    </body>
    </html>
