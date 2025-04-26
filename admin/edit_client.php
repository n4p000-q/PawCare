<?php
// Enable error reporting
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Check if session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db.php';
require_once '../includes/header.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = "Edit Client";

if (!isset($_GET['id'])) {
    header('Location: manage_clients.php');
    exit;
}

$id = $_GET['id'];

// Fetch client data with owner information
try {
    $stmt = $pdo->prepare("
        SELECT u.user_id, u.username, u.email, u.created_at, 
               o.Owner_ID, o.Name, o.Contact as Phone, o.Email
        FROM Users u
        JOIN Owners o ON u.owner_id = o.Owner_ID
        WHERE u.user_id = ? AND u.role = 'client'
    ");
    $stmt->execute([$id]);
    $client = $stmt->fetch();

    if (!$client) {
        header('Location: manage_clients.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching client: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Update Owners table
        $stmt = $pdo->prepare("UPDATE Owners SET Name = ?, Contact = ? WHERE Owner_ID = ?");
        $stmt->execute([$name, $phone, $client['Owner_ID']]);
        
        // Update Users table
        $stmt = $pdo->prepare("UPDATE Users SET username = ?, email = ? WHERE user_id = ?");
        $stmt->execute([$username, $email, $id]);
        
        $pdo->commit();
        $success = "Client updated successfully!";
        
        // Refresh client data
        $stmt = $pdo->prepare("
            SELECT u.user_id, u.username, u.email, u.created_at, 
                   o.Owner_ID, o.Name, o.Contact as Phone, o.Email
            FROM Users u
            JOIN Owners o ON u.owner_id = o.Owner_ID
            WHERE u.user_id = ? AND u.role = 'client'
        ");
        $stmt->execute([$id]);
        $client = $stmt->fetch();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error updating client: " . $e->getMessage();
    }
}
?>

<div class="admin-container">
    <h2><i class="fas fa-user-edit"></i> Edit Client</h2>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <div class="edit-client-form">
        <form method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($client['Name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($client['username']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($client['Phone']) ?>" required>
            </div>
            
            <button type="submit" class="btn-primary">Update Client</button>
            <a href="manage_clients.php" class="btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>