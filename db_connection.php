<?php
$servername = "127.0.0.1"; // Your server name
$username = "adrian"; // Your database username
$password = ""; // Your database password
$dbname = "zero"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
