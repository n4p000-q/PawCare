<?php
session_start();
require_once 'config/db.php';

// Redirect logged-in users to their appropriate dashboard
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'client':
            header("Location: client-portal/");
            break;
        case 'admin':
        case 'vet':
        case 'staff':
            header("Location: admin/dashboard.php");
            break;
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome | Paws & Care Vet</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="welcome-page">
    <div class="welcome-container">
        <header>
            <img src="assets/images/logo-large.png" alt="Paws & Care Logo" width="200">
            <h1>Welcome to Paws & Care</h1>
            <p>Professional veterinary management system</p>
        </header>

        <div class="auth-options">
            <a href="auth/login.php" class="btn btn-primary btn-large">
                <i class="fas fa-sign-in-alt"></i> Staff Login
            </a>
            <a href="client-portal/" class="btn btn-secondary btn-large">
                <i class="fas fa-user"></i> Client Portal
            </a>
        </div>

        <div class="welcome-footer">
            <p>New client? <a href="auth/register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>