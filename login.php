<?php
session_start(); // Start the session

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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST['username'];
    $passInput = $_POST['password'];

    // Fetch user from the database
    $stmt = $conn->prepare("SELECT * FROM zero_users WHERE username = ?");
    $stmt->bind_param("s", $userInput);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if ($user && password_verify($passInput, $user['password'])) {
            // Successful login, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: home.php'); // Redirect to homepage
            exit;
        } else {
            $message = 'Invalid username or password.';
        }
    } else {
        $message = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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

        /* New style for the blurred login container */
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

        .totoro {
            position: absolute;
            left: 20px;
            top: 189px;
            z-index: -1;
        }

        .totoro img {
            width: 50px;
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

        .forgot {
            color: #e57373;
            font-size: 14px;
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

        .new-user {
            color: #e57373;
            font-size: 14px;
        }

        /* Style for hyperlinks */
        a {
            color: blue; /* Set other hyperlinks to a different color */
            text-decoration: none; /* Remove underline */
        }

        a:hover {
            text-decoration: underline; /* Underline on hover for clarity */
        }

        /* Style for the help text */
        .help-text {
            position: absolute; /* Positioning it at the bottom */
            bottom: 10px; /* Adjust based on preference */
            left: 50%; /* Center horizontally */
            color: #e57373;
            transform: translateX(-50%); /* Center align */
            font-size: 14px;
        }

        /* Style for the contact admin link */
        .contact-admin {
            color: skyblue; /* Set the color to sky blue */
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

        <div class="totoro">
            <img src="totoro.png" alt="Totoro">
        </div>

        <?php if (!empty($message)): ?>
            <div class="feedback"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- New blurred container -->
        <div class="login-container">
            <form class="login-form" method="POST">
                <label for="username"></label>
                <input type="text" id="username" name="username" placeholder="Enter your Username" required>

                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Enter your Password" required>
                <p class="forgot">
                    <a href="forgot_password.php">Forgot password?</a>
                </p>
                <button type="submit">Login</button>
                <p class="new-user">new user? <a href="register.php">create an account.</a></p>
            </form>
        </div>
    </div>

    <div class="help-text">
        need help? <a href="https://www.facebook.com/profile.php?id=100014715274721"><span class="contact-admin">contact admin</span></a>
    </div>
</body>

</html>
