<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $creation_date = date('Y-m-d H:i:s');
    $updation_date = $creation_date;  // Set the same time initially

    if (!empty($name)) {
        $sql = "INSERT INTO author (name, creation_date, updation_date) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sss', $name, $creation_date, $updation_date);

        if ($stmt->execute()) {
            header('Location: admin_author.php');  // Redirect to the author management page
            exit;
        } else {
            $error_message = "Error adding author. Please try again.";
        }
    } else {
        $error_message = "Author name cannot be empty.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author</title>
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
        <h1>Add New Author</h1>

        <?php if (isset($error_message)) : ?>
            <p style="color:red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="add_author.php">
            <label for="name">Author Name:</label>
            <input type="text" name="name" id="name" required>
            <button type="submit">Add Author</button>
        </form>
    </div>
</body>
</html>
