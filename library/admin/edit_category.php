<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

$servername = "localhost";
$username = "root"; // change to your DB username
$password = ""; // change to your DB password
$dbname = "lib"; // change to your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is set in the URL query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch current category data
    $sql = "SELECT * FROM categories WHERE c_id = ?"; // Updated to 'c_id'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();

    if (!$category) {
        die("Category not found.");
    }
} else {
    die("Category ID is required.");
}

// Handle form submission to update the category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];

    // Update category in the database
    $update_sql = "UPDATE categories SET c_name = ?, status = ?, updation_date = NOW() WHERE c_id = ?"; // Updated to 'c_name' and 'c_id'
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $name, $status, $id);

    if ($stmt->execute()) {
        header("Location: admin_categories.php"); // Redirect back to categories list
        exit;
    } else {
        echo "Error updating category.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="edit_category.css">
    <!-- <link rel="stylesheet" href="categories.css"> -->
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
        <div class="content_e">
            <h1>Edit Category</h1>

            <form method="POST" action="edit_category.php?id=<?php echo $id; ?>">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <!-- Updated to use 'c_name' -->
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($category['c_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" name="status" id="status" value="<?php echo htmlspecialchars($category['status']); ?>" required>
                </div>
                <button type="submit">Update Category</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
