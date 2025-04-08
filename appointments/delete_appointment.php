<?php
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $appt_id = $_GET['id'];
    
    try {
        // Soft delete (update status instead of full deletion)
        $stmt = $pdo->prepare("UPDATE Appointments SET Status='Cancelled' WHERE Appt_ID=?");
        $stmt->execute([$appt_id]);
        
        header("Location: list_appointments.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Appointment ID not specified!");
}
?>