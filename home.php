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

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['repost_id']) && !isset($_POST['like_post_id'])) {
    $content = isset($_POST['content']) ? $_POST['content'] : ''; // Make content optional
    $user_id = $_SESSION['user_id'];
    $error = '';

    // Initialize image path
    $targetFile = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/"; // Directory to store uploaded images
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $targetFile = $targetDir . uniqid() . '.' . $imageFileType; // Unique filename

        // Check if the file is an image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Move the uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Successfully uploaded the image
            } else {
                $error = "Error uploading your image.";
            }
        } else {
            $error = "File is not an image.";
        }
    }

    // Insert the post into the database with or without the image path
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $content, $targetFile);

    if ($stmt->execute()) {
        header("Location: home.php"); // Redirect to home page after posting
        exit();
    } else {
        $error = "Error posting your message.";
    }
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

    if ($stmt->execute()) {
        header("Location: home.php"); // Redirect to home page after reposting
        exit();
    } else {
        $error = "Error reposting your message.";
    }
}

// Handle post liking
if (isset($_POST['like_post_id'])) {
    $liked_post_id = $_POST['like_post_id'];
    $user_id = $_SESSION['user_id'];

    // Insert a like entry into the database
    $stmt = $conn->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $liked_post_id);

    if ($stmt->execute()) {
        header("Location: home.php"); // Redirect to home page after liking
        exit();
    } else {
        $error = "Error liking the post.";
    }
}

// Fetch posts from the database
$sql = "SELECT posts.id, posts.content, posts.created_at, posts.image_path, 
        zero_users.username AS user_username 
        FROM posts 
        JOIN zero_users ON posts.user_id = zero_users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);

// Function to convert time to 'time ago' format
function timeAgo($datetime, $timezone = 'Asia/Manila') {
    $now = new DateTime("now", new DateTimeZone($timezone));
    $postTime = new DateTime($datetime, new DateTimeZone($timezone));
    $interval = $now->diff($postTime);

    if ($interval->y > 0) return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    if ($interval->m > 0) return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    if ($interval->d > 0) return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    if ($interval->h > 0) return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    if ($interval->i > 0) return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    return 'just now';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Social Media</title>
    <style>
        @font-face {
            font-family: 'CustomMiniFont';
            src: url('fonts/mini.otf') format('opentype');
        }

        body {
            margin: 0;
            font-family: 'CustomMiniFont', 'Comic Sans MS', cursive, sans-serif;
            background-color: #D7B18D; /* Light brown background */
            padding: 20px;
        }

        header {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            text-align: center;
            max-width: 600px; /* Increased max-width for better layout */
            margin: 0 auto;
        }

        .create-post {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 5px;
            margin-bottom: 20px; /* Increase the gap between create post and posts */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 90%; /* Reduced width for a shorter look */
            margin: 0 auto; /* Center the create post section */
        }

        textarea {
            width: 70%; /* Full width */
            height: 40px;
            margin-bottom: 5px;
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 5px;
            font-family: 'CustomMiniFont', 'Comic Sans MS', cursive, sans-serif;
            font-weight: bold;
        }

        input[type="file"] {
            display: none;
        }

        .file-label {
            cursor: pointer;
            margin-bottom: 0px;
            margin-right: 185px;
        }

        .post {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 25px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px; /* Space above each post */
            text-align: left;
        }

        button {
            width: 135px;
            padding: 12px;
            margin: 10px 0;
            font-size: 18px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'CustomMiniFont', 'Comic Sans MS', cursive, sans-serif;
        }

        button:hover {
            background-color: #555;
        }

        .post-content {
            font-size: 14px; /* Adjusted font size */
            font-weight: bold;
        }

        .post-image {
            max-width: 100%;
            height: auto;
            border-radius: 25px;
            margin-top: 10px;
        }

        .like-button, .repost-button {
            border: none;
            background: none;
            cursor: pointer;
        }

        .like-button.liked img {
    filter: brightness(1.2); /* Makes the liked heart slightly brighter */
    transition: filter 0.2s; /* Smooth transition effect */
}

/* You can add additional styles as needed */

        
        .icons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    <script>
        function repost(postId) {
            const formData = new FormData();
            formData.append('repost_id', postId);

            fetch('home.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    location.reload(); // Reload the page on success
                } else {
                    alert('Failed to repost.'); // Show error message on failure
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function like(postId) {
    const likeButton = document.querySelector(`.like-button[data-post-id='${postId}']`);
    const isLiked = likeButton.classList.toggle('liked');

    // Change the image based on the like state
    const heartImage = isLiked ? 'hearted.png' : 'heart.png';
    likeButton.querySelector('img').src = heartImage;

    // Send AJAX request to like/unlike the post
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'like.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Update like count display
                likeButton.innerHTML = `<img src="${heartImage}" alt="Like" style="width: 20px;"> ${response.like_count}`;
            }
        }
    };
    xhr.send(`postID=${postId}&liked=${isLiked}`);
}

    </script>
</head>

<body>
    <header>
        <h1>Welcome to Your Home</h1>
    </header>
    <div class="container">
        <div class="create-post">
            <form action="" method="post" enctype="multipart/form-data">
                <textarea name="content" placeholder="What's on your mind?"></textarea>
                <label for="image" class="file-label">
            <img src="img.png" alt="Upload Image" style="width: 30px; cursor: pointer;">
            <input type="file" id="image" name="image" accept="image/*" class="hidden-file-input">
        </label>
                <button type="submit">Post</button>
            </form>
        </div>

        <?php if (isset($error) && !empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
    <div class="post-content">
        <strong><?= htmlspecialchars($row['user_username']); ?></strong>
        <span>(<?= timeAgo($row['created_at']); ?>)</span>
        <p><?= nl2br(htmlspecialchars($row['content'])); ?></p>
        <?php if ($row['image_path']): ?>
            <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="Post Image" class="post-image">
        <?php endif; ?>
    </div>
    <div class="icons">
        <!-- Assuming this is inside a loop where $row contains your post data -->
<button class="like-button" data-post-id="<?= $row['id']; ?>" onclick="like(<?= $row['id']; ?>)">
    <img src="heart.png" alt="Like" style="width: 20px;"> <?= isset($row['like_count']) ? $row['like_count'] : 0; ?>
</button>
<button class="repost-button" onclick="repost(<?= $row['id']; ?>)">
    <img src="share.png" alt="Repost" style="width: 20px;">
</button>

    </div>
</div>


            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>
</body>

</html>
