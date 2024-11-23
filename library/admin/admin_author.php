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

// Build SQL query for authors
$sql = "SELECT * FROM author WHERE a_name LIKE '%$search%'"; // Updated to 'a_name'
$result = $mysqli->query($sql);  // Execute the query and store the result
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEACE</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="categories.css">  <!-- Assuming you have a separate CSS file for the Author page -->
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
        <h1>Manage Authors</h1>

        <!-- Search Form -->
        <form method="POST" action="admin_author.php">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search Authors">
            <button type="submit">Search</button>
            <a href="add_author.php" class="add-category-btn">Add Author</a>
        </form>

        <!-- Authors Table -->
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Author Name</th>
                    <th>Creation Date</th>
                    <th>Updation Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['a_id']; ?></td> <!-- Updated to 'a_id' -->
                            <td><?php echo $row['a_name']; ?></td> <!-- Updated to 'a_name' -->
                            <td><?php echo $row['creation_date']; ?></td>
                            <td><?php echo $row['updation_date']; ?></td>
                            <td>
                                <a class="edit-btn" href="edit_author.php?id=<?php echo $row['a_id']; ?>">Edit</a> | 
                                <a class="delete-btn" href="delete_author.php?id=<?php echo $row['a_id']; ?>" onclick="return confirm('Are you sure you want to delete this author?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr><td colspan="5">No authors found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$mysqli->close();  // Close the database connection
?>
