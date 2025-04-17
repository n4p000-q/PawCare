<?php
require_once '../config/db.php';

// Fetch all upcoming appointments with pet/vet details
$stmt = $pdo->query("
    SELECT a.Appt_ID, a.Date, a.Time, a.Reason, a.Status,
           p.Name AS PetName, v.Name AS VetName, o.Name AS OwnerName
    FROM Appointments a
    JOIN Pets p ON a.Pet_ID = p.Pet_ID
    JOIN Owners o ON p.Owner_ID = o.Owner_ID
    LEFT JOIN Vets v ON a.Vet_ID = v.Vet_ID
    WHERE a.Date >= CURDATE()
    ORDER BY a.Date, a.Time
");
$appointments = $stmt->fetchAll();

$page_title = "See Appointments";
require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Upcoming Appointments</h1>
    <a href="add_appointment.php" class="btn-add">New Appointment</a>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Pet</th>
                <th>Owner</th>
                <th>Vet</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appt): ?>
                <tr>
                    <td><?= htmlspecialchars($appt['Date']) ?></td>
                    <td><?= date('h:i A', strtotime($appt['Time'])) ?></td>
                    <td><?= htmlspecialchars($appt['PetName']) ?></td>
                    <td><?= htmlspecialchars($appt['OwnerName']) ?></td>
                    <td><?= htmlspecialchars($appt['VetName'] ?? 'Unassigned') ?></td>
                    <td><?= htmlspecialchars($appt['Reason']) ?></td>
                    <td>
                        <span class="status-<?= strtolower($appt['Status']) ?>">
                            <?= $appt['Status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_appointment.php?id=<?= $appt['Appt_ID'] ?>" class="btn-edit">Edit</a>
                        <a href="delete_appointment.php?id=<?= $appt['Appt_ID'] ?>" 
                           class="btn-delete"
                           onclick="return confirm('Cancel this appointment?')">
                           Cancel
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<?php 
require_once '../includes/footer.php';
?>