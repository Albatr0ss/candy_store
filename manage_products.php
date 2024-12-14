<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../includes/dbconnect.php';

// Fetch all products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll();

// Handle add, update, and delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = $_POST['price'];

        $stmt = $conn->prepare("INSERT INTO products (product_name, description, price) VALUES (?, ?, ?)");
        $stmt->execute([$name, $desc, $price]);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
</head>
<body>
    <h1>Manage Products</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['product_name'] ?></td>
                <td><?= $product['description'] ?></td>
                <td><?= $product['price'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
