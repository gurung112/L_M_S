<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: admin_index.php');
    exit;
}

include('config.php');

// Initialize variables
$username = $email = $password = $mobile = $status = "";
$errorMessage = "";

// Form submission processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $mobile = trim($_POST['mobile']);

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($mobile)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Please enter a valid email address.";
    } elseif (!preg_match('/^\d{10}$/', $mobile)) {  // Mobile number should be exactly 10 digits
        $errorMessage = "Please enter a valid 10-digit mobile number.";
    } elseif (strlen($password) < 8) {  // Password should be at least 8 characters long
        $errorMessage = "Password must be at least 8 characters long.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL query to insert data into the userss table (including mobile)
        if ($stmt = $mysqli->prepare("INSERT INTO userss (username, email, password, mobile) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $mobile);

            if ($stmt->execute()) {
                // Redirect to user management page after successful insert
                header('Location: admin_usermanagement.php');
                exit;
            } else {
                $errorMessage = "Error: Could not add user. Please try again. " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errorMessage = "Database error: Could not prepare statement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="abc.css">
    <link rel="stylesheet" href="content.css">
    <link rel="stylesheet" href="addcategory.css">
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
        <a href="admin_categories.php">Categories</a>
        <a href="admin_author.php">Author</a>
        <a href="admin_bookrequest.php">Book Requests</a>
        <a href="admin_usermanagement.php">User Management</a>
    </div>
    <div class="content">
        <h1>Add New User</h1>

        <!-- Error message -->
        <?php if ($errorMessage): ?>
            <div class="error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <!-- User Form -->
        <form method="POST" action="add_user.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" name="mobile" id="mobile" value="<?php echo htmlspecialchars($mobile); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <button type="submit">Add User</button>
            </div>
        </form>
    </div>
</body>
</html>
