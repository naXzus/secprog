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

// Retrieve order and total cost from session
$order = isset($_SESSION["order"]) ? $_SESSION["order"] : [];
$total_cost = isset($_SESSION["total_cost"]) ? $_SESSION["total_cost"] : 0;

// Clear session data after retrieving
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .confirmation {
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
    <div class="confirmation">
        <h2>Order Confirmation</h2>
        <?php if (!empty($order)): ?>
            <table>
                <tr>
                    <th>Flavor</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                </tr>
                <?php foreach ($order as $flavor => $quantity): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $flavor))); ?></td>
                        <td><?php echo htmlspecialchars($quantity); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($quantity * $cookie_prices[$flavor], 2)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <p><strong>Total Cost:</strong> $<?php echo htmlspecialchars(number_format($total_cost, 2)); ?></p>
        <?php else: ?>
            <p>No items ordered.</p>
        <?php endif; ?>
        <button onclick="window.location.href='order.php'">Order More</button>
    </div>
</body>
</html>
