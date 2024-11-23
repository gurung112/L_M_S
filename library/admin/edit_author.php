<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

$servername = "localhost";
$username = "root"; // Change to your DB username
$password = ""; // Change to your DB password
$dbname = "lib"; // Change to your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is set in the URL query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch current author data
    $sql = "SELECT * FROM author WHERE a_id = ?"; // Updated to 'a_id'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $author = $result->fetch_assoc();

    if (!$author) {
        die("Author not found.");
    }
} else {
    die("Author ID is required.");
}

// Handle form submission to update the author
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    // Update author in the database
    $update_sql = "UPDATE author SET a_name = ?, updation_date = NOW() WHERE a_id = ?"; // Updated to 'a_name' and 'a_id'
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $name, $id);

    if ($stmt->execute()) {
        header("Location: admin_author.php"); // Redirect back to authors list
        exit;
    } else {
        echo "Error updating author.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Author</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="edit_author.css"> <!-- You can create a separate CSS for this page -->
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
            <h1>Edit Author</h1>

            <form method="POST" action="edit_author.php?id=<?php echo $id; ?>">
                <div class="form-group">
                    <label for="name">Author Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($author['a_name']); ?>" required> <!-- Updated to 'a_name' -->
                </div>
                <button type="submit">Update Author</button>
            </form>
        </div>
    </div>
</body>
</html>t7

<?php
$conn->close();
?>
