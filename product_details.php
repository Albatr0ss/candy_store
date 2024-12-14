<?php
include '../includes/dbconnect.php';
include '../includes/header.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Product not found.");
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Product Details</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container product-detail">
        <?php
        // Define the image path based on the database value
        $imagePath = '../assets/img/' . htmlspecialchars($product['image_url']);
        
        // Check if the image exists at the specified path
        if (!file_exists($imagePath)) {
            echo '<p>Image not found!</p>';
        } else {
            // Display the product image if it exists
            echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($product['product_name']) . '">';
        }
        
        ?>

        <!-- Display product name -->
        <h1><?= htmlspecialchars($product['product_name']) ?></h1>

        <!-- Display product description -->
        <p><?= htmlspecialchars($product['description']) ?></p>

        <!-- Display product price -->
        <p class="price">Price: $<?= htmlspecialchars($product['price']) ?></p>

        <!-- Add to cart form -->
        <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
