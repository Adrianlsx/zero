<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Database configuration
$host = '127.0.0.1';
$dbname = 'zero';
$username = 'adrian'; // Your database username
$password = ''; // Your database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id']; // Get the user ID from the session
    $content = $_POST['content'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $userId, $content);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the home page after posting
        header('Location: home.php');
        exit;
    } else {
        echo "Error: " . $stmt->error; // Display error if something goes wrong
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
