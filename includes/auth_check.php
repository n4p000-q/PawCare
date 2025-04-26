<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Store the requested URL for redirecting after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: /login.php");
    exit();
}

// Optional: Add additional checks for specific pages if needed
// For example, to restrict clients from staff pages:
/*
if (strpos($_SERVER['REQUEST_URI'], '/staff/') !== false && $_SESSION['role'] !== 'staff') {
    header("HTTP/1.1 403 Forbidden");
    die("Access denied. Staff only area.");
}
*/
?>