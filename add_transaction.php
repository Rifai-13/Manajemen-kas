<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $type, $amount, $description])) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>
