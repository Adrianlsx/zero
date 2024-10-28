<?php
// Database configuration
$host = '127.0.0.1'; // Change if needed
$dbname = 'zero';
$db_username = 'adrian'; // Your database username
$db_password = ''; // Your database password

// Create connection
$conn = new mysqli($host, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize feedback message variable
$message = '';
$pin = ''; // Variable to hold the generated PIN

// Function to generate a unique random PIN
function generateUniquePin($conn) {
    do {
        // Generate a random 4-digit PIN
        $pin = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Check if the generated PIN already exists in the database
        $stmt = $conn->prepare("SELECT * FROM zero_users WHERE pin = ?");
        $stmt->bind_param("s", $pin);
        $stmt->execute();
        $result = $stmt->get_result();

    } while ($result->num_rows > 0); // Repeat until a unique PIN is found

    return $pin;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Check if passwords match
    if ($pass !== $confirm_pass) {
        $message = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM zero_users WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Username already taken.";
        } else {
            // Hash the password
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

            // Generate a unique random PIN
            $pin = generateUniquePin($conn);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO zero_users (username, password, pin) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user, $hashed_pass, $pin);

            if ($stmt->execute()) {
                // Successful registration
                $pinMessage = true; // Flag to show the PIN modal
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <style>
        /* Same styles as login.php */
        
        body {
            margin: 0;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('bg.gif') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        .container {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            z-index: 2;
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
        
        .register-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .register-form input {
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
        
        .register-form input::placeholder {
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

        /* Style for feedback message */
        .feedback {
            color: #e57373;
            margin-bottom: 10px;
        }

        /* Modal styles */
        .modal {
            display: <?php echo isset($pinMessage) ? 'block' : 'none'; ?>; /* Show modal if flag is set */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            margin: 15% auto;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        .close {
            color: #333; /* Adjust color for visibility */
            float: right;
            font-size: 28px;
            font-weight: bold;
            font-family: 'Comic Sans MS', cursive, sans-serif; /* Comic Sans MS for the close button */
        }

        .close:hover,
        .close:focus {
            color: #555; /* Change color on hover */
            text-decoration: none;
            cursor: pointer;
        }

        .pin-message {
            color: #e57373; /* Set to #e57373 for warning */
            font-weight: bold;
        }

        .forgot {
            color: #e57373;
        }

        /* Style for hyperlinks */
        a {
            color: blue; /* Set other hyperlinks to a different color */
            text-decoration: none; /* Remove underline */
        }

        a:hover {
            text-decoration: underline; /* Underline on hover for clarity */
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

        <!-- Registration form container -->
        <div class="register-container">
            <form class="register-form" method="POST">
                <label for="username"></label>
                <input type="text" id="username" name="username" placeholder="Choose a Username" required>

                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Choose a Password" required>

                <label for="confirm_password"></label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your Password" required>

                <button type="submit">Register</button>
                <p class="forgot">Already have an account? <a href="login.php">Log in here.</a></p>
            </form>
        </div>
    </div>

    <!-- Modal for PIN message -->
    <div class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.querySelector('.modal').style.display='none';">&times;</span>
            <div class="pin-message">Warning: This is your PIN to your account: <strong><?php echo $pin; ?></strong>. Save it or RIP.</div>
        </div>
    </div>

    <script>
        // Close the modal when clicking anywhere outside of it
        window.onclick = function(event) {
            var modal = document.querySelector('.modal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

