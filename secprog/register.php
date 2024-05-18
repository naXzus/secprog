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

// Registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    if (!preg_match("/^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.{6,})/", $password)) {
        $notification_type = "error";
        $notification_message = "Password must contain at least 6 characters, including one capital letter and one special character";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);

    if ($stmt->execute()) {
         
            // Successful registration
            $notification_type = "success";
            $notification_message = "Registration successful";
        } else {
            // Failed registration
            $notification_type = "error";
            $notification_message = "Error occured while registration";
        }
}
}

// Close connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

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
input[type="tel"],
input[type="email"],

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
    <h2>Registration</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="reg_username">Username:</label><br>
        <input type="text" id="reg_username" name="username" required><br>
        <label for="reg_email">Email:</label><br>
        <input type="email" id="reg_email" name="email" required><br>
        <label for="reg_phone">Phone:</label><br>
        <input type="tel" id="reg_phone" name="phone" pattern="[0-9]{10}" required><br>
        <label for="reg_password">Password:</label><br>
        <input type="password" id="reg_password" name="password" required><br><br>
        <input type="submit" value="Register" name="register">
        <button onclick="window.location.href='login.php'">Back to Login</button>
    </form>
</div>

<!-- Button to go back to login page -->


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

?>
