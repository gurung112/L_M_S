<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php'); // Include the database connection

// Search functionality
$search = "";
if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
}

// Build SQL query
$sql = "SELECT * FROM categories WHERE c_name LIKE '%$search%'"; // Updated to use 'c_name' column
$result = $mysqli->query($sql);  // Execute the query and store the result

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEACE</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="categories.css">
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
        <h1>Manage Categories</h1>

        <!-- Search Form -->
        <form method="POST" action="admin_categories.php">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Categories">
            <button type="submit">Search</button>
            <a href="add_category.php" class="add-category-btn">Add Category</a>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Creation Date</th>
                    <th>Updation Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['c_id']; ?></td> <!-- Updated to 'c_id' -->
                            <td><?php echo $row['c_name']; ?></td> <!-- Updated to 'c_name' -->
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['creation_date']; ?></td>
                            <td><?php echo $row['updation_date']; ?></td>
                            <td>
                                <!-- Updated link to reflect the 'c_id' -->
                                <a class="edit-btn" href="edit_category.php?id=<?php echo $row['c_id']; ?>">Edit</a> | 
                                <a class="delete-btn" href="delete_category.php?id=<?php echo $row['c_id']; ?>" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr><td colspan="6">No categories found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$mysqli->close();  // Close the database connection
?>
