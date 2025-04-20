<?php
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: ../auth/login.php");
    exit();
}

// Role-based access control example
$allowed_roles = ['admin', 'vet', 'staff', 'client']; // Adjust per page
if (isset($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
    header("HTTP/1.1 403 Forbidden");
    die("Access denied. Your role: " . $_SESSION['role']);
}
?>