<?php
require_once '../config/db.php';
require_once '../includes/header.php';

$error = '';
$success = '';
$step = $_GET['step'] ?? 'request'; // request | verify | update

// Step 1: Request reset link
if ($step === 'request' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Generate token (store in database with expiration)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $pdo->prepare("
            INSERT INTO PasswordResets (user_id, token, expires_at)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE token = ?, expires_at = ?
        ")->execute([$user['user_id'], $token, $expires, $token, $expires]);
        
        // In a real app, send email here
        $success = "If an account exists, a reset link has been sent (simulated)";
    } else {
        $success = "If an account exists, a reset link has been sent (simulated)";
    }
}

// Step 2: Verify token
if ($step === 'verify' && isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $pdo->prepare("
        SELECT u.user_id FROM PasswordResets r
        JOIN Users u ON r.user_id = u.user_id
        WHERE r.token = ? AND r.expires_at > NOW()
    ");
    $stmt->execute([$token]);
    
    if (!$stmt->fetch()) {
        $error = "Invalid or expired reset link";
        $step = 'request';
    }
}

// Step 3: Update password
if ($step === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
        $step = 'verify';
    } else {
        $stmt = $pdo->prepare("
            SELECT u.user_id FROM PasswordResets r
            JOIN Users u ON r.user_id = u.user_id
            WHERE r.token = ? AND r.expires_at > NOW()
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE Users SET password = ? WHERE user_id = ?")
                ->execute([$hashed_password, $user['user_id']]);
            $pdo->prepare("DELETE FROM PasswordResets WHERE token = ?")
                ->execute([$token]);
                
            $success = "Password updated successfully! <a href='login.php'>Login now</a>";
            $step = 'complete';
        } else {
            $error = "Invalid or expired reset link";
            $step = 'request';
        }
    }
}
?>

<div class="auth-container">
    <h2><i class="fas fa-key"></i> Password Reset</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if ($step === 'request'): ?>
        <form method="POST">
            <p>Enter your email to receive a reset link:</p>
            <label>Email</label>
            <input type="email" name="email" required>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
        </form>
    
    <?php elseif ($step === 'verify'): ?>
        <form method="POST" action="?step=update">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
            <label>New Password</label>
            <input type="password" name="password" minlength="8" required>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    
    <?php elseif ($step === 'complete'): ?>
        <p>Your password has been updated successfully.</p>
        <a href="login.php" class="btn btn-primary">Return to Login</a>
    <?php endif; ?>
    
    <div class="auth-links">
        <a href="login.php">Back to Login</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>