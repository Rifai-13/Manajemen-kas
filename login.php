<?php
require 'db.php';
session_start();

$username_error = "";
$password_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $username_error = "Invalid username";
        $password_error = "Invalid password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username"><br>
            <div class="error-message"><?php echo $username_error; ?></div> <!-- Menampilkan pesan kesalahan -->
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password"><br>
            <div class="error-message"><?php echo $password_error; ?></div> <!-- Menampilkan pesan kesalahan -->
            <br>
            <input type="submit" value="Login">
        </form>
        <a href="index.php">Kembali</a>
    </div>
</body>
</html>