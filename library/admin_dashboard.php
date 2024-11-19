<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

// Include database connection
include('config.php');

// Fetch the list of books
$books_query = $mysqli->query("SELECT * FROM books");
$books = $books_query->fetch_all(MYSQLI_ASSOC);

// Fetch the list of book requests
$requests_query = $mysqli->query("SELECT br.request_id, u.username, b.title, br.request_date, br.status 
                                  FROM book_requests br
                                  JOIN userss u ON br.user_id = u.user_id
                                  JOIN books b ON br.book_id = b.book_id");
$requests = $requests_query->fetch_all(MYSQLI_ASSOC);

// Fetch total users
$total_users_result = $mysqli->query("SELECT COUNT(*) AS user_count FROM users");
$total_users_data = $total_users_result->fetch_assoc();
$total_users = $total_users_data['user_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEACE</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="content.css">
    <link rel="shortcut icon" href="image/abc.jpg" type="image/x-icon">
</head>
<body>
    <div class="heading">
        <h1>PEACE</h1>
        <a href="logout.php"><b>Logout</b></a>
    </div>
    <div class="navbar">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_library.php">Library</a>
        <a href="admin_bookrequest.php">Book Requests</a>
        <a href="admin_usermanagement.php">User Management</a>
    </div>
    <div class="content">
        <div class="content_a">
            <!-- Display Dashboard Information -->

        <h2>Dashboard Overview</h2>
        <p>Total Users: <?php echo $total_users; ?></p>

        <!-- List of Books in the Library -->
        <h3>Library Books</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo $book['year']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
