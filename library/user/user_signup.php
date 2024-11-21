<?php
// Database connection details
include('config.php');

// Variable to store messages
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
    $mobile = $_POST['mobile'];

    // Debugging: Check the values being submitted
    var_dump($user, $email, $mobile); // Check what values are being sent

    // SQL query to insert data
    $sql = "INSERT INTO userss (username, email, password, mobile) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssss", $user, $email, $password, $mobile);

    if ($stmt->execute()) {
        // Redirect to login page with a success message
        header("Location: user_login.php?message=success");
        exit(); // Ensure the script stops executing after redirect
    } else {
        $message = "<div class='error'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<div class="navbar">
        <h1>Library Management System</h1>
        <a href="../home.html">Home</a>
        <a href="user_login.php">User Login</a>
        <a href="user_signup.php">User SignUp</a>
        <a href="../admin/admin_index.php">Admin Login</a>
    </div>
    <div class="page">
        <div class="container">
            <h2>User SignUp</h1>
            <?php echo $message; ?>
            <form action="user_signup.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="mobile">Mobile</label>
                <input type="tel" id="mobile" name="mobile" required>

                <button type="submit">Register</button>
            </form>
        </div>
    </div>
    
</body>
</html>
