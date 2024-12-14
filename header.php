<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and their role (customer or admin)
$userLoggedIn = isset($_SESSION['user']);
$userRole = $userLoggedIn ? $_SESSION['user']['role'] : null; // 'customer' or 'admin'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Candy Craft</title>
</head>
<body>
<header>
    <div class="logo">
        <a href="./index.php"><h1>Candy Craft</h1></a>
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="customer/product_list.php">Shop</a></li>
            <?php if ($userRole === 'customer'): ?>
                <li><a href="customer/cart.php">Cart</a></li>
                <li><a href="customer/order_history.php">Orders</a></li>
            <?php elseif ($userRole === 'admin'): ?>
                <li><a href="admin/dashboard.php">Dashboard</a></li>
                <li><a href="admin/manage_products.php">Manage Products</a></li>
                <li><a href="admin/manage_orders.php">Manage Orders</a></li>
            <?php endif; ?>
            <?php if ($userLoggedIn): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
