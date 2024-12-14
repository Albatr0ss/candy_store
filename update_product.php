<?php
session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "candystore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the product ID is passed in the URL
if (!isset($_GET['id'])) {
    echo "No product ID provided.";
    exit();
}

$product_id = $_GET['id'];

// Fetch the product details
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit();
}

// Handle form submission for updating the product
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload if a new image is provided
    $image_url = $product['image_url']; // Default to existing image
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        $image_error = $image['error'];

        if ($image_error === 0) {
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_ext_lower = strtolower($image_ext);
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_ext_lower, $allowed_exts) && $image_size < 5000000) {
                $new_image_name = uniqid('', true) . "." . $image_ext_lower;
                $image_upload_path = 'uploads/' . $new_image_name;

                if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                    $image_url = $image_upload_path; // Update with new image path
                } else {
                    echo "Error uploading the new image.";
                }
            } else {
                echo "Invalid image format or size. Only JPG, JPEG, PNG, and GIF under 5MB are allowed.";
            }
        }
    }

    // Update the product in the database
    $update_stmt = $conn->prepare("UPDATE products SET product_name = ?, description = ?, price = ?, image_url = ? WHERE product_id = ?");
    $update_stmt->bind_param("ssdsi", $product_name, $description, $price, $image_url, $product_id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        echo "Product updated successfully!";
    } else {
        echo "No changes were made or an error occurred.";
    }

    $update_stmt->close();
    header("Location: admin.php"); // Redirect back to the admin panel
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #5a5a8b;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        form input, form textarea, form button {
            width: 96%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            background: #5a5a8b;
            color: #fff;
            font-size: 1rem;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background: #48486d;
        }

        img {
            display: block;
            margin: 10px auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        small {
            font-size: 0.9rem;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Update Product</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image">
        <small>Current Image:</small>
        <?php if (!empty($product['image_url'])): ?>
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" width="100">
        <?php else: ?>
            No Image
        <?php endif; ?>

        <button type="submit">Update Product</button>
    </form>
</body>
</html>
