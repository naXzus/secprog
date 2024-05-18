<?php

// Set session configuration
ini_set('session.use_only_cookies', 1); // Use only cookies for session management
ini_set('session.cookie_httponly', 1); // Prevent session cookie from being accessed by JavaScript
ini_set('session.cookie_secure', 1); // Transmit session cookie only over HTTPS



// Start or resume session
session_start();

// Regenerate session ID to prevent session fixation
session_regenerate_id(true);


// Database connection
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "secprog"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

$notification_type = ""; // success or error
$notification_message = "";

// Login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);

    // Execute the query
    $stmt->execute();

    // Store result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful login
        $notification_type = "success";
        $notification_message = "Login successful";

         // Redirect to order page
         header("Location: order.php");
         exit;
        
    } else {
        // Failed login
        $notification_type = "error";
        $notification_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
    
}

.container {
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
}

form {
    margin-top: 20px;
}

input[type="text"],
input[type="password"],
input[type="submit"],

button {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
    justify-content: center;
    align-items: center;
}

button {
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

p {
    margin-top: 10px;
    text-align: center;
}
.notification {
            display: none;
            position: fixed;
            top: 50px;
            left: 50%;
            padding: 15px;
            transform: translateX(-50%);
            border-radius: 5px;
            color: white;
            z-index: 1000;
        }
        .success {
            background-color: #4CAF50;
        }
        .error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
<!-- Notification container -->
<div id="notification" class="notification <?php echo $notification_type; ?>"><?php echo $notification_message; ?></div>

    <div class="container">
    <h2>Login</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="login_username">Username:</label><br>
        <input type="text" id="login_username" name="username" required><br>
        <label for="login_password">Password:</label><br>
        <input type="password" id="login_password" name="password" required><br><br>
        <input type="submit" value="Login" name="login">
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    <p>Forgot your password? <a href="passrec.php">Recover password</a>.</p>
</div>

<!-- JavaScript for notification -->
<script>
        // Display notification
        window.onload = function() {
            var notification = document.getElementById('notification');
            if (notification.textContent !== '') {
                notification.style.display = 'block';
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 3000); // Hide after 3 seconds
            }
        };
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
