<?php
require_once '../config/db.php';
require_once '../includes/header.php';
session_start();

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = "Staff Management";

// Handle form submission for new staff
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $role]);
        $success = "Staff member added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding staff: " . $e->getMessage();
    }
}

// Fetch all staff members
$staff = $pdo->query("SELECT * FROM Users WHERE role != 'client' ORDER BY created_at DESC")->fetchAll();
?>

<div class="admin-container">
    <h2><i class="fas fa-users-cog"></i> Staff Management</h2>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <div class="staff-management">
        <div class="add-staff-form">
            <h3>Add New Staff Member</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="vet">Veterinarian</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Add Staff</button>
            </form>
        </div>
        
        <div class="staff-list">
            <h3>Current Staff Members</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                    <tr>
                        <td><?= $member['user_id'] ?></td>
                        <td><?= htmlspecialchars($member['username']) ?></td>
                        <td><?= htmlspecialchars($member['email']) ?></td>
                        <td><?= ucfirst($member['role']) ?></td>
                        <td><?= date('M j, Y', strtotime($member['created_at'])) ?></td>
                        <td>
                            <a href="edit_staff.php?id=<?= $member['user_id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="delete_staff.php?id=<?= $member['user_id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this staff member?')"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>