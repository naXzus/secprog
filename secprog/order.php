<?php
// Start or resume session
session_start();

// Regenerate session ID to prevent session fixation
session_regenerate_id(true);

// Set session configuration
ini_set('session.use_only_cookies', 1); // Use only cookies for session management
ini_set('session.cookie_httponly', 1); // Prevent session cookie from being accessed by JavaScript
ini_set('session.cookie_secure', 1); // Transmit session cookie only over HTTPS

// Check if user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Define cookie prices
$cookie_prices = [
    "chocolate" => 1.5,
    "red_velvet" => 2.0,
    "marshmallow" => 2.5,
    "pandan" => 1.8
];

// Initialize total cost
$total_cost = 0;

// Process the order form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order"])) {
    $order = [];
    foreach ($cookie_prices as $flavor => $price) {
        $quantity = isset($_POST[$flavor]) ? intval($_POST[$flavor]) : 0;
        if ($quantity > 0) {
            $order[$flavor] = $quantity;
            $total_cost += $quantity * $price;
        }
    }
    
    // Store the order in the session
    $_SESSION["order"] = $order;
    $_SESSION["total_cost"] = $total_cost;

    // Redirect to order confirmation page
    header("Location: confirm_order.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cookies</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            margin-bottom: 20px;
        }
        td, th {
            padding: 10px;
        }
    </style>
</head>
<body>
    <h2>Order Cookies</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <table>
            <tr>
                <th>Flavor</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
            <tr>
                <td>Chocolate</td>
                <td>$1.50</td>
                <td><input type="number" name="chocolate" min="0" value="0"></td>
            </tr>
            <tr>
                <td>Red Velvet</td>
                <td>$2.00</td>
                <td><input type="number" name="red_velvet" min="0" value="0"></td>
            </tr>
            <tr>
                <td>Marshmallow</td>
                <td>$2.50</td>
                <td><input type="number" name="marshmallow" min="0" value="0"></td>
            </tr>
            <tr>
                <td>Pandan</td>
                <td>$1.80</td>
                <td><input type="number" name="pandan" min="0" value="0"></td>
            </tr>
        </table>
        <input type="submit" value="Order" name="order">
    </form>
</body>
</html>
