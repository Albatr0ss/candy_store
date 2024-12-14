<?php
session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "candystore");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete product query
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Product deleted successfully!";
    } else {
        echo "Error deleting the product.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the admin panel
    header("Location: admin.php");
    exit();
}
?>
