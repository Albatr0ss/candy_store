<?php
session_start();
include '../includes/dbconnect.php';
include '../includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}

// Remove from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

// Fetch cart items
$cart_products = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $conn->query("SELECT * FROM products WHERE product_id IN ($ids)");
    $cart_products = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #ff4d6d;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .cart table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .cart th, .cart td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 1rem;
        }

        .cart th {
            background-color: #ff4d6d;
            color: #fff;
        }

        .cart td {
            background-color: #f9f9f9;
        }

        .cart tr:nth-child(even) td {
            background-color: #ffe4e1;
        }

        .cart td img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .cart .btn {
            background-color: #ff4d6d;
            color: #fff;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cart .btn:hover {
            background-color: #e14a5b;
        }

        .cart .total {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff4d6d;
            text-align: right;
            margin-top: 20px;
        }

        .cart a {
            display: inline-block;
            background-color: #ff4d6d;
            color: #fff;
            padding: 12px 20px;
            font-size: 1.2rem;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .cart a:hover {
            background-color: #e14a5b;
        }

        .cart .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart .actions form {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Cart</h1>
        <?php if (empty($cart_products)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table class="cart">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
                                <?= htmlspecialchars($product['product_name']) ?>
                            </td>
                            <td><?= $_SESSION['cart'][$product['product_id']] ?></td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td>$<?= number_format($_SESSION['cart'][$product['product_id']] * $product['price'], 2) ?></td>
                            <td>
                                <form method="POST" class="actions">
                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                    <button type="submit" name="remove_from_cart" class="btn">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="total">Total: $<?= number_format(array_sum(array_map(function ($product) {
                return $_SESSION['cart'][$product['product_id']] * $product['price'];
            }, $cart_products)), 2) ?></p>
            <a href="checkout.php">Proceed to Checkout</a>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
