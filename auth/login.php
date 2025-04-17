<?php
require_once '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate credentials
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Start secure session
        session_regenerate_id();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect based on role
        switch ($user['role']) {
            case 'client':
                header("Location: ../client-portal/");
                break;
            case 'admin':
                header("Location: ../admin/");
                break;
            default:
                header("Location: ../");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

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