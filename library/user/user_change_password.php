<?php
session_start();
include 'config.php'; // Include your database connection script

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

// Handle password update
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $message = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
    } else {
        // Fetch current password from the database
        $sql = "SELECT password FROM userss WHERE user_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // Verify current password
            if ($current_password === $row['password']) { // Adjust if hashing is used
                
                // Prevent reusing the same password
                if ($current_password === $new_password) {
                    $message = "The new password cannot be the same as the current password.";
                } else {
                    // Update the password in the database
                    $update_sql = "UPDATE userss SET password = ? WHERE user_id = ?";
                    $update_stmt = $mysqli->prepare($update_sql);
                    $update_stmt->bind_param('si', $new_password, $user_id);

                    if ($update_stmt->execute()) {
                        $message = "Password changed successfully.";
                    } else {
                        $message = "Failed to update password. Please try again.";
                    }
                }
            } else {
                $message = "Current password is incorrect.";
            }
        } else {
            $message = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/user_home.css"> 
    <link rel="stylesheet" href="css/input.css"> 
</head>
<body>
    <div class="navbar">
        <h1>Library Management System</h1>
        <a href="user_home.php">Home</a>
        <a href="user_books.php">Books</a>
        <a href="user_profile.php">Profile</a>
        <a href="user_change_password.php">Change Password</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="page">
        <div class="container">
            <h2>Change Password</h2>
            <!-- Display the message above the form -->
            <?php if (!empty($message)) : ?>
                <p class="<?= strpos($message, 'successfully') !== false ? 'message' : 'error'; ?>">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
            <form method="POST" action="user_change_password.php">
                <input type="password" name="current_password" id="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Change Password</button>
            </form>
        </div>
    </div>
</body>
</html>
