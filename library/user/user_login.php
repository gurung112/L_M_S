<?php
include('config.php');

// Variable to store messages
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch user details
    $sql = "SELECT * FROM userss WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $message = "<div class='success'>Login successful! Welcome, " . htmlspecialchars($user['username']) . ".</div>";
        } else {
            $message = "<div class='error'>Invalid password. Please try again.</div>";
        }
    } else {
        $message = "<div class='error'>No user found with this email address.</div>";
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
    <title>User Login</title>
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
            <h2>User Login</h1>
            <?php echo $message; ?>
            <form action="login.php" method="POST">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
