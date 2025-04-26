<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';

// Verify that the current user is an admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../auth/unauthorized.php');
    exit();
}

$page_title = "Register New Admin";
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Username or email already exists.';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new admin
            $stmt = $pdo->prepare("INSERT INTO Users (username, password, email, role) VALUES (?, ?, ?, 'admin')");
            if ($stmt->execute([$username, $hashed_password, $email])) {
                $success = 'New admin registered successfully!';
                // Clear form fields
                $_POST = [];
            } else {
                $error = 'An error occurred. Please try again.';
            }
        }
    }
}
?>

<style>
        /* Admin specific styles */
.admin-container {
    display: flex;
    min-height: calc(100vh - 120px);
}

.admin-sidebar {
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 20px 0;
}

.admin-sidebar nav ul {
    list-style: none;
    padding: 0;
}

.admin-sidebar nav li a {
    display: block;
    padding: 10px 20px;
    color: white;
    text-decoration: none;
    transition: background 0.3s;
}

.admin-sidebar nav li a:hover {
    background: #34495e;
}

.admin-sidebar nav li.active a {
    background: #3498db;
}

.admin-content {
    flex: 1;
    padding: 20px;
}

.admin-form {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn-admin {
    display: inline-block;
    background: #2c3e50;
    color: white;
    padding: 10px 15px;
    border-radius: 4px;
    text-decoration: none;
    margin: 10px 0;
}

.btn-admin:hover {
    background: #34495e;
}

.alert {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-error {
    background: #ffdddd;
    color: #d8000c;
}

.alert-success {
    background: #ddffdd;
    color: #4f8a10;
}
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> | Paws & Care</title>
    <!-- <link rel="stylesheet" href="../assets/css/style.css"> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="admin-container">
        <aside class="admin-sidebar">
            <nav>
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
                    <li class="active"><a href="register_admin.php"><i class="fas fa-user-plus"></i> Register Admin</a></li>
                    <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="admin-content">
            <h1><i class="fas fa-user-plus"></i> Register New Admin</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form action="register_admin.php" method="post" class="admin-form">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Register Admin</button>
            </form>
        </main>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>