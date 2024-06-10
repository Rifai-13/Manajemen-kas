<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'daily';
$date = new DateTime();
if ($type == 'weekly') {
    $date->modify('-1 week');
} elseif ($type == 'monthly') {
    $date->modify('-1 month');
}

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND created_at >= ?");
$stmt->execute([$_SESSION['user_id'], $date->format('Y-m-d H:i:s')]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Report</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Generate Report</h2>
    <form method="get" action="generate_report.php">
        <label for="type">Report Type:</label><br>
        <select id="type" name="type">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
        </select><br><br>
        <input type="submit" value="Generate">
    </form>
    <h3>Transactions</h3>
    <table>
        <tr>
            <th>Type</th>
            <th>Amount (RP)</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
        <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td><?= htmlspecialchars($transaction['type']) ?></td>
            <td><?= number_format($transaction['amount'], 2) ?></td>
            <td><?= htmlspecialchars($transaction['description']) ?></td>
            <td><?= htmlspecialchars($transaction['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
