<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/db.php';
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>POST data: "; print_r($_POST); echo "</pre>"; // Debug POST data

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate credentials
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    echo "<pre>User data: "; print_r($user); echo "</pre>"; // Debug user data

    if ($user && password_verify($password, $user['password'])) {
        // Start secure session
        session_regenerate_id();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        
        echo "Session: "; print_r($_SESSION); // Debug session

        // Redirect based on role
        /*
        switch ($user['role']) {
            case 'client':
                header("Location: ../client-portal/index.php");
                break;
            case 'admin':
                header("Location: ../admin/dashboard.php");  // Updated path
                break;
            case 'vet':
            case 'staff':
                header("Location: ../admin/dashboard.php");  // Added specific paths
                break;
            default:
                header("Location: ../index.php");
        }*/

        // Redirect based on role
        $redirect = match($user['role']) {
            'client' => '/client-portal/index.php',
            'admin' => '/admin/dashboard.php',
            'vet', 'staff' => '/admin/dashboard.php',
            default => '/index.php'
        };
        
        echo "Redirecting to: $redirect"; // Debug redirect path
        header("Location: $redirect");

        exit();
    } else {
        $error = "Invalid username or password";
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!-- Rest of your login form HTML remains the same -->

<div class="auth-container">
    <h2><i class="fas fa-paw"></i> Vet System Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    </form>

    <div class="auth-links">
        <a href="register.php">Register</a> | 
        <a href="reset_password.php">Forgot Password?</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>