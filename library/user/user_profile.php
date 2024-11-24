<?php
session_start();

include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

// Fetch the logged-in user's details
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$query = "SELECT * FROM userss WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if form is submitted to update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $image = $_FILES['image'];
    
    $image_path = $user['image']; // Default to existing image

    // Handle image upload if a file is provided
    if ($image['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image['name']);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type (optional)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_type, $allowed_types)) {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }

        // Move file to uploads directory
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            $image_path = $target_file; // Update image path
        } else {
            die("Failed to upload image. Check directory permissions.");
        }
    }

    // Update user details in the database
    $update_query = "UPDATE userss SET username = ?, email = ?, mobile = ?, image = ? WHERE user_id = ?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param("ssssi", $username, $email, $mobile, $image_path, $user_id);

    if ($stmt->execute()) {
        // Refresh the page to load updated details
        header("Location: user_profile.php");
        exit();
    } else {
        die("Error updating profile: " . $stmt->error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/user_home.css"> 
    <link rel="stylesheet" href="css/profile.css"> 
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
    <div class="profile-container">
        <h1>User Profile</h1>
        <div class="profile-details">
            <!-- Display Profile Picture -->
            <img src="<?php echo !empty($user['image']) ? htmlspecialchars($user['image']) : '../images/users.png'; ?>" 
                 alt="Profile Picture" class="profile-image">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($user['mobile']); ?></p>
        </div>
        <button onclick="document.getElementById('edit-form').style.display='block'">Edit Profile</button>
    </div>

    <!-- Edit Form -->
    <div id="edit-form" class="edit-form" style="display: none;">
        <h2>Edit Profile</h2>
        <form action="user_profile.php" method="POST" enctype="multipart/form-data">
            <label for="username">Name:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
            
            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <button type="submit">Save Changes</button>
            <button type="button" onclick="document.getElementById('edit-form').style.display='none'">Cancel</button>
        </form>
    </div>
</body>
</html>
