<?php
// Restrict to admins only
$allowed_roles = ['admin'];
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role']; // admin, vet, staff
    $temp_password = bin2hex(random_bytes(4)); // Generates like "3a7f9b2c"
    
    $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO Users (username, password, email, role) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$username, $hashed_password, $email, $role]);
        
        // In real app, send email with temp password here
        $success = "Account created! Temporary password: $temp_password";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch existing staff
$staff = $pdo->query("
    SELECT user_id, username, email, role 
    FROM Users 
    WHERE role IN ('admin', 'vet', 'staff')
")->fetchAll();
?>

<div class="admin-container">
    <h2><i class="fas fa-user-shield"></i> Staff Accounts</h2>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="card">
        <h3>Create New Staff Account</h3>
        <form method="POST">
            <label>Username*</label>
            <input type="text" name="username" required>
            
            <label>Email*</label>
            <input type="email" name="email" required>
            
            <label>Role*</label>
            <select name="role" required>
                <option value="vet">Veterinarian</option>
                <option value="staff">Staff Member</option>
                <option value="admin">Administrator</option>
            </select>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>
    </div>
    
    <div class="card">
        <h3>Existing Staff</h3>
        <table class="staff-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= ucfirst($user['role']) ?></td>
                    <td>
                        <a href="edit_staff.php?id=<?= $user['user_id'] ?>" 
                           class="btn btn-edit btn-small">
                           Edit
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>