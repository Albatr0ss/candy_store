<?php
session_start();
include 'includes/dbconnect.php'; // Include the database connection
include 'includes/header.php'; // Include the header

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    if (empty($email) || empty($password)) {
        $errors[] = "Email and password are required.";
    } else {
        try {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    // Store user details in session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role']; // Role can be 'admin' or 'customer'

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit;
                } else {
                    $errors[] = "Invalid email or password.";
                }
            } else {
                $errors[] = "No user found with this email.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="login-container">
    <h1 class="login-title">Login</h1>
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="login-form">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" class="form-input" required>

        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" class="form-input" required>

        <button type="submit" class="submit-button">Login</button>
    </form>
</div>

<?php include 'includes/footer.php'; // Include the footer ?>
