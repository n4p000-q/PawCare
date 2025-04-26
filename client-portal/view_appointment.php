<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';

// [Similar auth checks as above]

// Get appointment ID from URL
$appt_id = $_GET['id'] ?? 0;

// Verify appointment belongs to client and get details
$stmt = $pdo->prepare("
    SELECT a.*, p.Name AS pet_name, p.Species, p.Breed,
           v.Name AS vet_name, v.Specialization, v.Contact AS vet_contact,
           o.Name AS owner_name, o.Contact AS owner_contact, o.Email AS owner_email
    FROM Appointments a
    JOIN Pets p ON a.Pet_ID = p.Pet_ID
    JOIN Owners o ON p.Owner_ID = o.Owner_ID
    LEFT JOIN Vets v ON a.Vet_ID = v.Vet_ID
    WHERE a.Appt_ID = ? AND o.Owner_ID = ?
");
$stmt->execute([$appt_id, $user['owner_id']]);
$appointment = $stmt->fetch();

if (!$appointment) {
    header("Location: upcoming_appointments.php");
    exit();
}

// [Rest of the view template similar to upcoming_appointments.php but with more detail]
?>