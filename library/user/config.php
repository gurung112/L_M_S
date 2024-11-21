<?php

$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "lib"; // Your database name

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>