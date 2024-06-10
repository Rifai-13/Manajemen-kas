<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mendapatkan informasi pengguna
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Mendapatkan data transaksi
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();

// Mendapatkan data pendapatan harian, mingguan, dan bulanan
$dailyIncome = $pdo->query("SELECT DATE(created_at) as date, SUM(amount) as total FROM transactions WHERE type = 'income' GROUP BY DATE(created_at)")->fetchAll();
$weeklyIncome = $pdo->query("SELECT YEARWEEK(created_at, 1) as week, SUM(amount) as total FROM transactions WHERE type = 'income' GROUP BY YEARWEEK(created_at, 1)")->fetchAll();
$monthlyIncome = $pdo->query("SELECT MONTH(created_at) as month, SUM(amount) as total FROM transactions WHERE type = 'income' GROUP BY MONTH(created_at)")->fetchAll();
?>



<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="navbar">
        <div class="navbar-left">
            <h2>Dashboard</h2>
            <div class="menu">
                <a href="add_food.php">Add Food</a>
                <a href="order_food.php">Order Food</a>
                <a href="manage_orders.php">Manage Orders</a>
            </div>
        </div>
        <div class="navbar-right">
            <div class="dropdown">
                <button class="dropbtn">
                    <img src="img/profile-pic.jpg" alt="Profile Picture" class="profile-pic">
                </button>
                <div class="dropdown-content">
                    <p><?= htmlspecialchars($user['username']) ?></p>
                    <a href="settings.php">Settings</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="charts">
            <div class="chart-container">
                <h3>Pendapatan Harian</h3>
                <canvas id="dailyIncomeChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Pendapatan Mingguan</h3>
                <canvas id="weeklyIncomeChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Pendapatan Bulanan</h3>
                <canvas id="monthlyIncomeChart"></canvas>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Jumlah</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction) : ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['id']) ?></td>
                        <td><?= htmlspecialchars($transaction['amount']) ?></td>
                        <td><?= htmlspecialchars($transaction['type']) ?></td>
                        <td><?= htmlspecialchars($transaction['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <footer>© 2024 Manajemen Kas</footer>
    <script>
        const dailyIncomeData = <?= json_encode($dailyIncome) ?>;
        const weeklyIncomeData = <?= json_encode($weeklyIncome) ?>;
        const monthlyIncomeData = <?= json_encode($monthlyIncome) ?>;
    </script>
    <script src="js/dashboard.js"></script>
</body>

</html>