<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';

// [Similar auth checks as above]

$appt_id = $_GET['id'] ?? 0;

// Verify appointment belongs to client
$check = $pdo->prepare("
    SELECT a.Appt_ID FROM Appointments a
    JOIN Pets p ON a.Pet_ID = p.Pet_ID
    WHERE a.Appt_ID = ? AND p.Owner_ID = ?
");
$check->execute([$appt_id, $user['owner_id']]);

if ($check->rowCount() > 0) {
    // Update status to cancelled rather than delete
    $stmt = $pdo->prepare("UPDATE Appointments SET Status = 'Cancelled' WHERE Appt_ID = ?");
    $stmt->execute([$appt_id]);
    
    $_SESSION['success'] = "Appointment cancelled successfully";
}

header("Location: upcoming_appointments.php");
exit();
?>