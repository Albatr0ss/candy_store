<?php
session_start();
include '../includes/dbconnect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit;
    }

    // Get the user ID from session
    $user_id = $_SESSION['user_id'];
    $total_amount = 0;

    // Check if the cart is not empty
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

        // Calculate total order amount
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();

            // If product exists, calculate the total
            if ($product) {
                $total_amount += $product['price'] * $quantity;
            } else {
                // Handle case where product doesn't exist (optional)
                echo "Product not found: Product ID " . $product_id;
                exit;
            }
        }

        // Insert the order into the orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total, order_date) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $total_amount]);
        $order_id = $conn->lastInsertId();

        // Insert order details into the order_items table
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Fetch product price
            $stmt = $conn->prepare("SELECT price FROM products WHERE product_id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            $price = $product['price'];

            // Insert each item into the order_items table
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $product_id, $quantity, $price]);
        }

        // Clear the cart after successful order
        unset($_SESSION['cart']);

        // Provide a success message
        echo "<p>Order placed successfully!</p>";
    } else {
        // Handle case when cart is empty
        echo "<p>Your cart is empty. Please add items to the cart before proceeding.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <form method="POST">
            <button type="submit" name="checkout">Place Order</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
