<?php
session_start();
$host = '127.0.0.1';
$dbname = 'zero';
$username = 'adrian'; // Your database username
$password = ''; // Your database password

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postID = $_POST['postID'];
    $liked = $_POST['liked'];
    $userID = $_SESSION['user_id']; // Assuming you store the user ID in the session

    if ($liked) {
        // Insert like into the database
        $stmt = $conn->prepare("INSERT INTO likes (postID, userID) VALUES (?, ?)");
        $stmt->bind_param("ii", $postID, $userID);
        $stmt->execute();
    } else {
        // Remove like from the database
        $stmt = $conn->prepare("DELETE FROM likes WHERE postID = ? AND userID = ?");
        $stmt->bind_param("ii", $postID, $userID);
        $stmt->execute();
    }

    // Get the new like count for the post
    $stmt = $conn->prepare("SELECT COUNT(*) as like_count FROM likes WHERE postID = ?");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    $likeData = $result->fetch_assoc();
    
    // Return the new like count as JSON
    echo json_encode(['success' => true, 'like_count' => $likeData['like_count']]);
    
    $stmt->close();
}
?>
