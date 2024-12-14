<?php
session_start();

// Redirect if not logged in or not an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Add product logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle image upload
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    // Check for image upload errors
    if ($image_error === 0) {
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_ext_lower = strtolower($image_ext);

        // Allow only certain image formats
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_ext_lower, $allowed_exts)) {
            if ($image_size < 5000000) { // Limit to 5MB
                $new_image_name = uniqid('', true) . "." . $image_ext_lower;
                $image_upload_path = 'uploads/' . $new_image_name;

                if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                    $conn = new mysqli("localhost", "root", "", "candystore");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, image_url) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssds", $product_name, $description, $price, $image_upload_path);
                    $stmt->execute();

                    echo "Product added successfully!";
                    header('Location: admin.php');
                    $stmt->close();
                    $conn->close();
                } else {
                    echo "Error uploading the image.";
                }
            } else {
                echo "Image is too large. Max size is 5MB.";
            }
        } else {
            echo "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "Error uploading image.";
    }
}

// Fetch products for management
$conn = new mysqli("localhost", "root", "", "candystore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h1, h2 {
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

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background: #5a5a8b;
            color: #fff;
        }

        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        table img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }

        a {
            color: #5a5a8b;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin!</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required><br>

        <button type="submit">Add Product</button>
    </form>

    <h2>Manage Products</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" width="100">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td>$<?= htmlspecialchars(number_format($product['price'], 2)) ?></td>
                        <td>
                            <a href="update_product.php?id=<?= urlencode($product['product_id']) ?>">Edit</a> |
                            <a href="delete_product.php?id=<?= urlencode($product['product_id']) ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No products available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
