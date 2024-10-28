<?php
session_start();
require 'db_connection.php'; // Include your database connection file

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Fetch user profile data
$stmt = $conn->prepare("SELECT u.username, p.bio, p.profile_picture FROM zero_users u LEFT JOIN profiles p ON u.id = p.user_id WHERE u.id = ?");
$stmt->bind_param("s", $user_id); // Bind the user ID
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Comic Sans MS', sans-serif; /* Ensure Comic Sans MS is used */
            background-color: #D8CFC4; /* Light brown background */
            color: #333;
        }
        .profile-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-container img {
            width: 150px;
            height: auto;
            border-radius: 75px; /* Circular profile picture */
        }
        .bio {
            margin-top: 15px;
            font-size: 16px;
            line-height: 1.5;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50; /* Green button */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h1><?php echo htmlspecialchars($row['username']); ?>'s Profile</h1>
    <img src="<?php echo htmlspecialchars($row['profile_picture'] ? $row['profile_picture'] : 'default_profile.png'); ?>" alt="Profile Picture" />
    <p class="bio">Bio: <?php echo htmlspecialchars($row['bio'] ? $row['bio'] : 'No bio available.'); ?></p>

    <h2>Edit Profile</h2>
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <textarea name="bio" placeholder="Write your bio..."><?php echo htmlspecialchars($row['bio']); ?></textarea>
        <input type="file" name="profile_picture" accept="image/*" />
        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>

<?php
$stmt->close(); // Close the statement
$conn->close(); // Close the connection
?>
