<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="css/user_home.css"> 
    <link rel="stylesheet" href="css/style.css"> 
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

    <!-- Main Content Section -->
    <div class="main-content">
        <section class="welcome-section">
            <h2>Welcome to the Library</h2>
            <p>Explore a wide range of books, request the ones you love, and stay updated with the latest library news.</p>
        </section>

        <section class="featured-books">
            <h2>Featured Books</h2>
            <div class="books-list">
                <!-- Example Featured Books -->
                <div class="book-item">
                    <img src="images/book1.jpg" alt="Book 1">
                    <h3>Book Title 1</h3>
                    <p>Author: John Doe</p>
                </div>
                <div class="book-item">
                    <img src="images/book2.jpg" alt="Book 2">
                    <h3>Book Title 2</h3>
                    <p>Author: Jane Smith</p>
                </div>
                <div class="book-item">
                    <img src="images/book3.jpg" alt="Book 3">
                    <h3>Book Title 3</h3>
                    <p>Author: Mark Johnson</p>
                </div>
            </div>
        </section>

        <section class="library-news">
            <h2>Latest Library News</h2>
            <ul>
                <li><a href="#">New Arrivals in the Library – Explore Now!</a></li>
                <li><a href="#">Join our Upcoming Book Club Event</a></li>
                <li><a href="#">Special Discount on Late Fees This Month</a></li>
            </ul>
        </section>

        <section class="reading-tips">
            <h2>Reading Tips</h2>
            <ul>
                <li><a href="#">How to Build a Reading Habit</a></li>
                <li><a href="#">5 Tips for Speed Reading</a></li>
                <li><a href="#">Choosing the Best Books for Your Interests</a></li>
            </ul>
        </section>

        <section class="recommendations">
            <h2>Book Recommendations</h2>
            <p>Based on your reading preferences, we recommend:</p>
            <ul>
                <li><strong>Book Title 1</strong> by Author 1</li>
                <li><strong>Book Title 2</strong> by Author 2</li>
                <li><strong>Book Title 3</strong> by Author 3</li>
            </ul>
        </section>
    </div>

</body>
</html>
