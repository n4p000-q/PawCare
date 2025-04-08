<?php
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $owner_id = $_GET['id'];
    
    try {
        // Delete owner (and their pets via ON DELETE CASCADE)
        $stmt = $pdo->prepare("DELETE FROM Owners WHERE Owner_ID = ?");
        $stmt->execute([$owner_id]);
        
        header("Location: list_owners.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Owner ID not specified!");
}
?>