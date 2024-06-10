<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO foods (name, price) VALUES (?, ?)");
    if ($stmt->execute([$name, $price])) {
        echo "Food added successfully.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Food</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h2>Add Food</h2>
        <form method="post" action="add_food.php">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name"><br>
            <label for="price">Price (RP):</label><br>
            <input type="text" id="price" name="price"><br><br>
            <input type="submit" value="Add Food">
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

</body>

</html>