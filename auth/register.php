<?php
require_once '../config/db.php';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $owner_id = $_POST['owner_id'] ?? null; // For linking to existing owner

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        try {
            // Check if username/email exists
            $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Username or email already exists";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Create new client account
                $stmt = $pdo->prepare("
                    INSERT INTO Users (username, password, email, role, owner_id)
                    VALUES (?, ?, ?, 'client', ?)
                ");
                $stmt->execute([$username, $hashed_password, $email, $owner_id]);
                
                $success = "Registration successful! You can now login.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch existing owners for linking
$owners = $pdo->query("SELECT Owner_ID, Name, Email FROM Owners")->fetchAll();
?>

<div class="auth-container">
    <h2><i class="fas fa-user-plus"></i> Client Registration</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <label>Username*</label>
        <input type="text" name="username" required>
        
        <label>Email*</label>
        <input type="email" name="email" required>
        
        <label>Password* (min 8 characters)</label>
        <input type="password" name="password" minlength="8" required>
        
        <label>Confirm Password*</label>
        <input type="password" name="confirm_password" required>
        
        <label>Link to Existing Owner (optional)</label>
        <select name="owner_id">
            <option value="">-- Create New Owner --</option>
            <?php foreach ($owners as $owner): ?>
                <option value="<?= $owner['Owner_ID'] ?>">
                    <?= htmlspecialchars($owner['Name']) ?> (<?= $owner['Email'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
        
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Register
        </button>
    </form>
    
    <div class="auth-links">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>