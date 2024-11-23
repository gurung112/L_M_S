<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

// Include database connection
include('config.php');

// Fetch total books
$total_books_result = $mysqli->query("SELECT COUNT(*) AS book_count FROM books");
$total_books_data = $total_books_result->fetch_assoc();
$total_books = $total_books_data['book_count'];

// Fetch total categories
$total_categories_result = $mysqli->query("SELECT COUNT(*) AS category_count FROM categories");
$total_categories_data = $total_categories_result->fetch_assoc();
$total_categories = $total_categories_data['category_count'];

// Fetch total authors
$total_authors_result = $mysqli->query("SELECT COUNT(*) AS author_count FROM author");
$total_authors_data = $total_authors_result->fetch_assoc();
$total_authors = $total_authors_data['author_count'];

// Fetch total users
$total_users_result = $mysqli->query("SELECT COUNT(*) AS user_count FROM userss");
$total_users_data = $total_users_result->fetch_assoc();
$total_users = $total_users_data['user_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEACE Dashboard</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="content.css">
    <link rel="stylesheet" href="navbar.css">
    <style>
        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin: 20px 0;
        }

        .card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 200px;
            height: 180px; /* Adjusted to fit the image */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-align: center;
        }

        .card img {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .card h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 8px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        /* Image size */
        .card img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="heading">
        <h1>PEACE</h1>
        <a href="logout.php"><b>Logout</b></a>
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
        <h2>Dashboard Overview</h2>
        <div class="dashboard-cards">
            <div class="card" onclick="location.href='admin_library.php'">
                <img src="../images/books.jpg" alt="Books">
                <h3>Books</h3>
                <p><?php echo $total_books; ?></p>
            </div>
            <div class="card" onclick="location.href='admin_categories.php'">
                <img src="../images/category.png" alt="Categories">
                <h3>Categories</h3>
                <p><?php echo $total_categories; ?></p>
            </div>
            <div class="card" onclick="location.href='admin_author.php'">
                <img src="../images/authors.png" alt="Authors">
                <h3>Authors</h3>
                <p><?php echo $total_authors; ?></p>
            </div>
            <div class="card" onclick="location.href='admin_usermanagement.php'">
                <img src="../images/users.png" alt="Users">
                <h3>Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$mysqli->close();
?>
