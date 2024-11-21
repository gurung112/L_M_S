<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include the database connection

// Initialize variables
$name = $status = "";
$errorMessage = "";

// Form submission processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $status = $_POST['status'];

    // Validate input
    if (empty($name) || empty($status)) {
        $errorMessage = "All fields are required.";
    } else {
        // Insert the new category into the database (MySQL will handle creation and update dates)
        $stmt = $mysqli->prepare("INSERT INTO categories (name, status) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $status);

        if ($stmt->execute()) {
            // Redirect to categories page after successful insertion
            header('Location: admin_categories.php');
            exit;
        } else {
            $errorMessage = "Failed to add the category. Please try again.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="addcategory.css">
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
        <div class="content_d">
        <h1>Add New Category</h1>

        <!-- Error message -->
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Category Form -->
        <form method="POST" action="add_category.php">
            <label for="name">Category Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
            
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="active" <?php echo $status == 'Active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $status == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>

            <button type="submit">Add Category</button>
        </form>        
        </div>
    </div>
</body>
</html>

<?php
$mysqli->close();  // Close the database connection
?>
