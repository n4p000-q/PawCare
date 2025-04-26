<?php
require_once '../config/db.php';
require_once '../includes/header.php';
session_start();

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = "Edit Staff Member";

if (!isset($_GET['id'])) {
    header('Location: manage_staff.php');
    exit;
}

$id = $_GET['id'];

// Fetch staff member data
$stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->execute([$id]);
$staff = $stmt->fetch();

if (!$staff) {
    header('Location: manage_staff.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    
    try {
        $stmt = $pdo->prepare("UPDATE Users SET username = ?, email = ?, role = ? WHERE user_id = ?");
        $stmt->execute([$username, $email, $role, $id]);
        $success = "Staff member updated successfully!";
        
        // Refresh staff data
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
        $stmt->execute([$id]);
        $staff = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Error updating staff: " . $e->getMessage();
    }
}
?>

<div class="admin-container">
    <h2><i class="fas fa-user-edit"></i> Edit Staff Member</h2>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="edit-staff-form">
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($staff['username']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" <?= $staff['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="vet" <?= $staff['role'] === 'vet' ? 'selected' : '' ?>>Veterinarian</option>
                    <option value="staff" <?= $staff['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Update Staff</button>
            <a href="manage_staff.php" class="btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>