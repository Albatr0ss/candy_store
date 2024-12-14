<?php
include '../includes/dbconnect.php';
include '../includes/header.php';

try {
    // Fetch all products from the database
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Handle exceptions and database errors gracefully
    $products = [];
    echo "<p>Error fetching products: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Products</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>All Products</h1>
        <div class="product-list">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <?php if (!empty($product['image_url'])): ?>
                            <!-- Ensure image path is correct; assuming 'uploads/' directory for images -->
                            <img src="../<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" class="product-image">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <p>Price: $<?= htmlspecialchars(number_format($product['price'], 2)) ?></p>
                        <a href="product_details.php?id=<?= urlencode($product['product_id']) ?>">View Details</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available at the moment. Please check back later!</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
