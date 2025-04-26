<?php
require_once '../config/db.php';
session_start();

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: manage_clients.php');
    exit;
}

$id = $_GET['id'];

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Get owner_id first
    $stmt = $pdo->prepare("SELECT owner_id FROM Users WHERE user_id = ?");
    $stmt->execute([$id]);
    $owner_id = $stmt->fetchColumn();
    
    if (!$owner_id) {
        throw new Exception("Client not found");
    }
    
    // Delete from Users table
    $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
    $stmt->execute([$id]);
    
    // Delete from Owners table
    $stmt = $pdo->prepare("DELETE FROM Owners WHERE Owner_ID = ?");
    $stmt->execute([$owner_id]);
    
    $pdo->commit();
    $_SESSION['success'] = "Client deleted successfully!";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Error deleting client: " . $e->getMessage();
}

header('Location: manage_clients.php');
exit;
?>