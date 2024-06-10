<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$foods = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $food_id = $_POST['food_id'];
    $quantity = $_POST['quantity'];

    $stmt = $pdo->prepare("SELECT name, price FROM foods WHERE id = ?");
    $stmt->execute([$food_id]);
    $food = $stmt->fetch();

    if ($food) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][$food_id] = [
            'name' => $food['name'],
            'price' => $food['price'],
            'quantity' => $quantity
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $total_price = 0;

    foreach ($_SESSION['cart'] as $food_id => $item) {
        $total_price += $item['price'] * $item['quantity'];
    }

    if ($total_price > 0) {
        $_SESSION['order'] = [
            'items' => $_SESSION['cart'],
            'total_price' => $total_price
        ];
        unset($_SESSION['cart']);
        header("Location: order_food.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complete_order'])) {
    $user_id = $_SESSION['user_id'];
    $paid_amount = $_POST['paid_amount'];
    $order = $_SESSION['order'];

    if ($paid_amount >= $order['total_price']) {
        $change = $paid_amount - $order['total_price'];

        foreach ($order['items'] as $food_id => $item) {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, food_id, quantity, total_price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $food_id, $item['quantity'], $item['price'] * $item['quantity']]);

            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'income', ?, ?)");
            $stmt->execute([$user_id, $item['price'] * $item['quantity'], 'Order of ' . $item['name']]);
        }

        echo "Order placed successfully.<br>";
        echo "Total Price: RP " . number_format($order['total_price'], 2) . "<br>";
        echo "Paid Amount: RP " . number_format($paid_amount, 2) . "<br>";
        echo "Change: RP " . number_format($change, 2) . "<br>";

        unset($_SESSION['order']);
    } else {
        echo "Paid amount is not sufficient.";
    }
}

// Mengambil daftar makanan
$stmt = $pdo->query("SELECT * FROM foods");
$foods = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Order Food</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<body>
    <div class="container">
        <h2>Order Food</h2>
        <?php if (isset($_SESSION['order'])) : ?>
            <h3>Order Details</h3>
            <ul>
                <?php foreach ($_SESSION['order']['items'] as $item) : ?>
                    <li><?= htmlspecialchars($item['name']) ?> - RP <?= number_format($item['price'], 2) ?> x <?= htmlspecialchars($item['quantity']) ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Total Price: RP <?= number_format($_SESSION['order']['total_price'], 2) ?></p>
            <form method="post" action="order_food.php">
                <label for="paid_amount">Paid Amount (RP):</label><br>
                <input type="number" id="paid_amount" name="paid_amount" required><br><br>
                <input type="submit" name="complete_order" value="Complete Order">
            </form>
        <?php else : ?>
            <form method="post" action="order_food.php">
                <label for="food_id">Food:</label><br>
                <select id="food_id" name="food_id">
                    <?php foreach ($foods as $food) : ?>
                        <option value="<?= $food['id'] ?>"><?= htmlspecialchars($food['name']) ?> - RP <?= number_format($food['price'], 2) ?></option>
                    <?php endforeach; ?>
                </select><br>
                <label for="quantity">Quantity:</label><br>
                <input type="number" id="quantity" name="quantity" required><br><br>
                <input type="submit" name="add_to_cart" value="Add to Cart">
            </form>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) : ?>
                <h3>Cart</h3>
                <ul>
                    <?php foreach ($_SESSION['cart'] as $item) : ?>
                        <li><?= htmlspecialchars($item['name']) ?> - RP <?= number_format($item['price'], 2) ?> x <?= htmlspecialchars($item['quantity']) ?></li>
                    <?php endforeach; ?>
                </ul>
                <form method="post" action="order_food.php">
                    <input type="submit" name="place_order" value="Place Order">
                </form>
            <?php endif; ?>
        <?php endif; ?>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

</body>

</html>