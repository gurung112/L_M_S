<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');  // Include database connection file

// Fetch users from the database
$query = "SELECT user_id, username,email FROM userss"; // Adjust the table name if needed
$result = $mysqli->query($query);

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

// Handle the deletion of a user
if (isset($_POST['delete_user_id'])) {
    $user_id_to_delete = $_POST['delete_user_id'];
    $delete_query = "DELETE FROM userss WHERE user_id = ?";
    $stmt = $mysqli->prepare($delete_query);
    $stmt->bind_param("i", $user_id_to_delete);
    
    if ($stmt->execute()) {
        // Redirect to the same page to refresh the list
        header('Location: admin_usermanagement.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
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
        <h1>User Management</h1>
        <div class="table">
            <table border="1">
                <thead>
                    <tr>
                        <th>S.N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $serial_no = 1; // Starting Serial Number
                    while ($row = $result->fetch_assoc()) { 
                    ?>
                    <tr>
                        <td><?php echo $serial_no++; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <!-- Form to delete user -->
                            <form method="POST" action="admin_usermanagement.php" style="display:inline;">
                                <input type="hidden" name="delete_user_id" value="<?php echo $row['user_id']; ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
