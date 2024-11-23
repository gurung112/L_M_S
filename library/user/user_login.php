<?php
include('login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/input.css">
     
    
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
        <div class="container   ">
            <form action="user_login.php" method="POST">
                <h2>User Login </h2>
                <input type="text" name="email" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
    