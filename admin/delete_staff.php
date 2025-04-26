<?php
require_once '../config/db.php';
session_start();

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: manage_staff.php');
    exit;
}

$id = $_GET['id'];

// Prevent deleting yourself
if ($_SESSION['user_id'] == $id) {
    $_SESSION['error'] = "You cannot delete your own account!";
    header('Location: manage_staff.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "Staff member deleted successfully!";
} catch (PDOException $e) {
    $_SESSION['error'] = "Error deleting staff: " . $e->getMessage();
}

header('Location: manage_staff.php');
exit;
?>