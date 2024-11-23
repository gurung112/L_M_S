<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include the database connection

// Search functionality
$search = "";
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
}

// SQL query to fetch books along with their category, author, and status
$sql = "SELECT br.request_id, b.book_id, b.title, b.year, b.status, c.c_name AS category, a.a_name AS author
        FROM book_requests br
        JOIN books b ON br.book_id = b.book_id
        INNER JOIN categories c ON b.category_id = c.c_id
        LEFT JOIN author a ON a.a_id = br.a_id
        WHERE b.title LIKE '%$search%'";  // Searching by book title
$result = $mysqli->query($sql);  // Execute the query and store the result

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management - PEACE</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="categories.css">
    <link rel="shortcut icon" href="image/abc.jpg" type="image/x-icon">
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
        <h1>Manage Library</h1>

        <!-- Search Form -->
        <form method="POST" action="admin_library.php">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Books">
            <button type="submit">Search</button>
            <a href="add_book.php" class="add-book-btn">Add Book</a>
        </form>

        <!-- Book Table -->
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) : ?>
                    <?php $sn = 1; ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <!-- Action Links for Edit and Delete -->
                                <a class="edit-btn" href="edit_book.php?id=<?php echo $row['book_id']; ?>">Edit</a> | 
                                <a class="delete-btn" href="delete_book.php?id=<?php echo $row['book_id']; ?>" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr><td colspan="7">No books found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$mysqli->close();  // Close the database connection
?>
