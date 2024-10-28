<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Not logged in"); // Prevent access if not logged in
}

// Database configuration
$host = '127.0.0.1';
$dbname = 'zero';
$username = 'adrian'; // Your database username
$password = ''; // Your database password

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle reposting
if (isset($_POST['repost_id'])) {
    $original_post_id = $_POST['repost_id'];
    $user_id = $_SESSION['user_id'];

    // Get the original post content, image path, and user_id
    $stmt = $conn->prepare("SELECT content, image_path, user_id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $original_post_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($original_content, $original_image_path, $original_user_id);
    $stmt->fetch();

    // Get original username
    $stmt = $conn->prepare("SELECT username FROM zero_users WHERE id = ?");
    $stmt->bind_param("i", $original_user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($original_username);
    $stmt->fetch();

    // Create the repost content
    $repost_content = "Reposted from " . htmlspecialchars($original_username) . ": " . $original_content;

    // Insert the repost into the database with original content and image path
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $repost_content, $original_image_path);
    $stmt->execute();
}

$conn->close(); // Close the database connection
?>
