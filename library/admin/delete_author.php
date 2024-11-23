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

    // Delete author from the database
    $delete_sql = "DELETE FROM author WHERE a_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_author.php"); // Redirect back to authors list after deletion
        exit;
    } else {
        echo "Error deleting author.";
    }
} else {
    echo "Author ID is required.";
}

$conn->close();
?>
