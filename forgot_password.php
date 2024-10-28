<?php
// Database configuration
$host = '127.0.0.1'; // Change if needed
$dbname = 'zero';
$username = 'adrian'; // Your database username
$password = ''; // Your database password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize feedback message variable
$message = '';
$showNewPasswordField = false;
$user = '';
$pin = ''; // Initialize pin variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['pin'])) {
        $user = $_POST['username'];
        $pin = $_POST['pin'];

        // Check if user exists and validate PIN
        $stmt = $conn->prepare("SELECT * FROM zero_users WHERE username = ? AND pin = ?");
        $stmt->bind_param("ss", $user, $pin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Correct PIN; show the new password field
            $showNewPasswordField = true;
        } else {
            $message = "Username or PIN is incorrect.";
        }
    }

    // Handle password change submission
    if (isset($_POST['new_password']) && !empty($user)) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE zero_users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $user);

        if ($stmt->execute()) {
            $message = "Password has been successfully updated.";
            $showNewPasswordField = false; // Reset to hide the new password field
            // Reset the username and PIN after successful password change
            $user = '';
            $pin = '';
        } else {
            $message = "Error updating password: " . $stmt->error; // Show error message
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        /* styles.css */
        
        body {
            margin: 0;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('bg.gif') no-repeat center center fixed; /* Use GIF as background */
            background-size: cover; /* Cover the entire body */
            position: relative; /* Make body relative for absolute positioning */
        }
        
        .container {
            text-align: center;
            position: relative;
            z-index: 1; /* Ensure container is above the GIF */
        }

        /* New style for the blurred container */
        .login-container {
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
            backdrop-filter: blur(10px); /* Apply blur effect */
            border-radius: 15px; /* Rounded corners */
            padding: 20px; /* Spacing inside the container */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
            z-index: 2; /* Ensure it is above the background */
        }
        
        .object img {
            width: 300px;
            margin: 0;
            padding-top: 20px;
        }
        
        .forgot {
            color: #e57373;
            font-size: 14px;
        }
        
        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .login-form input {
            width: 250px;
            padding: 10px;
            margin: 8px 0;
            border-radius: 15px;
            border: 1px solid #ccc;
            font-size: 16px;
            color: #8e8e8e;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }
        
        .login-form input::placeholder {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            font-weight: bold;
            color: #8e8e8e;
        }
        
        button {
            width: 260px;
            padding: 12px;
            margin: 10px 0;
            font-size: 18px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            font-weight: bold;
        }
        
        button:hover {
            background-color: #555;
        }

        /* Style for hyperlinks */
        a {
            color: blue; /* Set other hyperlinks to a different color */
            text-decoration: none; /* Remove underline */
        }

        a:hover {
            text-decoration: underline; /* Underline on hover for clarity */
        }

        /* Style for feedback message */
        .feedback {
            color: #e57373; /* Color for feedback messages */
            margin-bottom: 10px; /* Space below the feedback message */
        }
    </style>
</head>

<body>
    
    <div class="container">
        <div class="object">
            <img src="object.png" alt="Object">
        </div>

        <?php if (!empty($message)): ?>
            <div class="feedback"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- New blurred container -->
        <div class="login-container">
            <?php if (!$showNewPasswordField): ?>
                <form class="login-form" method="POST">
                    <label for="username"></label>
                    <input type="text" id="username" name="username" placeholder="Enter your Username" required>

                    <label for="pin"></label>
                    <input type="text" id="pin" name="pin" placeholder="Enter your PIN" required>

                    <button type="submit">Change Password</button>
                </form>
            <?php else: ?>
                <form class="login-form" method="POST">
                    <label for="new_password"></label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" required>
                    <button type="submit">Reset Password</button>
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user); ?>">
                    <input type="hidden" name="pin" value="<?php echo htmlspecialchars($pin); ?>">
                </form>
            <?php endif; ?>

            <p class="forgot">Remembered your password? <a href="login.php">Log in.</a></p>
        </div>
    </div>

</body>

</html>
