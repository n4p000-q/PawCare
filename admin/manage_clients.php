<?php
// Enable error reporting
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Check if session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify DB connection
require_once '../config/db.php';

// Check admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$page_title = "Client Management";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $required = ['username', 'email', 'password', 'name', 'contact'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $error = "All fields are required!";
            break;
        }
    }

    if (!isset($error)) {
        try {
            $pdo->beginTransaction();
            
            // Insert owner
            $stmt = $pdo->prepare("INSERT INTO Owners (Name, Contact, Email) VALUES (?, ?, ?)");
            $stmt->execute([
                trim($_POST['name']),
                trim($_POST['contact']),
                trim($_POST['email'])
            ]);
            $owner_id = $pdo->lastInsertId();
            
            // Insert user
            $stmt = $pdo->prepare("INSERT INTO Users (username, password, email, role, owner_id) VALUES (?, ?, ?, 'client', ?)");
            $stmt->execute([
                trim($_POST['username']),
                password_hash($_POST['password'], PASSWORD_DEFAULT),
                trim($_POST['email']),
                $owner_id
            ]);
            
            $pdo->commit();
            $success = "Client added successfully!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch clients
try {
    $clients = $pdo->query("
        SELECT u.user_id, u.username, u.email, u.created_at, 
               o.Name, o.Contact as Phone, o.Email
        FROM Users u
        JOIN Owners o ON u.owner_id = o.Owner_ID
        WHERE u.role = 'client'
        ORDER BY u.created_at DESC
    ")->fetchAll();
} catch (PDOException $e) {
    die("Error fetching clients: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Care | <?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="../assets/css/style2(homepage).css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="admin-container">
        <h2><i class="fas fa-user-friends"></i> Client Management</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="client-management">
            <div class="add-client-form">
                <h3>Add New Client</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
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
                        <label for="contact">Phone</label>
                        <input type="tel" id="contact" name="contact" required>
                    </div>
                    <button type="submit" class="btn-primary">Add Client</button>
                </form>
            </div>
            
            <div class="client-list">
                <h3>Current Clients</h3>
                <?php if (empty($clients)): ?>
                    <p>No clients found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['user_id']) ?></td>
                                <td><?= htmlspecialchars($client['Name']) ?></td>
                                <td><?= htmlspecialchars($client['username']) ?></td>
                                <td><?= htmlspecialchars($client['Email']) ?></td>
                                <td><?= htmlspecialchars($client['Phone']) ?></td>
                                <td><?= date('M j, Y', strtotime($client['created_at'])) ?></td>
                                <td>
                                    <a href="edit_client.php?id=<?= $client['user_id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_client.php?id=<?= $client['user_id'] ?>" class="btn-delete" onclick="return confirm('Are you sure? This will delete all associated pets and records.')"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>