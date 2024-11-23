<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the author name from the POST request and sanitize it
    $a_name = trim($_POST['a_name']);  // Updated column name 'a_name'
    $creation_date = date('Y-m-d H:i:s');
    $updation_date = $creation_date;  // Set the same time for updation

    // Check if the author name is not empty
    if (!empty($a_name)) {
        // Prepare the SQL query to insert a new author into the database
        $sql = "INSERT INTO author (a_name, creation_date, updation_date) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql); // Use $mysqli for the connection variable
        $stmt->bind_param('sss', $a_name, $creation_date, $updation_date);

        // Execute the query
        if ($stmt->execute()) {
            header('Location: admin_author.php');  // Redirect to the author management page
            exit;
        } else {
            // Display error message if the insert fails
            $error_message = "Error adding author. Please try again.";
        }
    } else {
        // Display error message if the author name is empty
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

        <!-- Form to add a new author -->
        <form method="POST" action="add_author.php">
            <label for="a_name">Author Name:</label>  <!-- Use updated field name -->
            <input type="text" name="a_name" id="a_name" required>  <!-- Use updated field name -->
            <button type="submit">Add Author</button>
        </form>
    </div>
</body>
</html>
