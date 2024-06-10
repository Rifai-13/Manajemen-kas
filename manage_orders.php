<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT o.id, o.quantity, o.total_price, o.created_at, f.name AS food_name, u.username AS customer_name FROM orders o JOIN foods f ON o.food_id = f.id JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Manage Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Food</th>
            <th>Quantity</th>
            <th>Total Price (RP)</th>
            <th>Order Time</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= htmlspecialchars($order['id']) ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['food_name']) ?></td>
            <td><?= htmlspecialchars($order['quantity']) ?></td>
            <td><?= number_format($order['total_price'], 2) ?></td>
            <td><?= htmlspecialchars($order['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
