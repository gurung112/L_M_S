<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEACE</title>
    <link rel="stylesheet" href="abc.css">
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
        <a href="admin_bookrequest.php">Book Requests</a>
        <a href="admin_usermanagement.php">User Management</a>
    </div>
    <div class="content">
        <h1>Library</h1>
        <p>This is a simple vertical navbar example.</p>
    </div>
</body>
</html>
