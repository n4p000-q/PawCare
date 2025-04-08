<?php
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];
    
    try {
        // Delete the pet (foreign key constraints will handle related records)
        $stmt = $pdo->prepare("DELETE FROM Pets WHERE Pet_ID = ?");
        $stmt->execute([$pet_id]);
        
        header("Location: list_pets.php"); // Redirect after deletion
        exit();
    } catch (PDOException $e) {
        die("Error deleting pet: " . $e->getMessage());
    }
} else {
    die("Pet ID not specified!");
}
?>