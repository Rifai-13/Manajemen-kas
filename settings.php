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

// Menangani form submit untuk memperbarui profil pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, full_name = ? WHERE id = ?");
    $stmt->execute([$username, $email, $full_name, $user_id]);

    // Mengupdate data pengguna yang sudah ada dengan yang baru
    $user['username'] = $username;
    $user['email'] = $email;
    $user['full_name'] = $full_name;

    $success_message = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <h2>Settings</h2>
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
        <h2>Profile Settings</h2>
        <?php if (isset($success_message)): ?>
            <p class="success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        <form method="post" action="settings.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>"><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br>
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>"><br>
            <input type="submit" name="update_profile" value="Update Profile">
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <footer>© 2024 Manajemen Kas</footer>
</body>
</html>
