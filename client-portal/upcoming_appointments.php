<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';

// Ensure only clients can access
if ($_SESSION['role'] !== 'client') {
    header("Location: /login.php");
    exit();
}

// Get client's owner_id
$stmt = $pdo->prepare("SELECT owner_id FROM Users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || !$user['owner_id']) {
    die("Your account is not properly linked to an owner record.");
}

// Get upcoming appointments
$appointments = $pdo->prepare("
    SELECT a.Appt_ID, a.Date, a.Time, a.Reason, a.Status, 
           p.Name AS pet_name, v.Name AS vet_name
    FROM Appointments a
    JOIN Pets p ON a.Pet_ID = p.Pet_ID
    LEFT JOIN Vets v ON a.Vet_ID = v.Vet_ID
    WHERE p.Owner_ID = ? AND a.Date >= CURDATE()
    ORDER BY a.Date, a.Time
");
$appointments->execute([$user['owner_id']]);
$upcomingAppointments = $appointments->fetchAll();

$page_title = "Upcoming Appointments";
require_once '../includes/client_header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Appointments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .appointments-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .status-scheduled { color: #4b7bec; }
        .status-confirmed { color: #20bf6b; }
        .status-cancelled { color: #eb3b5a; }
    </style>
</head>
<body>
    <div class="appointments-container">
        <h1><i class="fas fa-calendar-alt"></i> Upcoming Appointments</h1>
        
        <?php if (empty($upcomingAppointments)): ?>
            <div class="alert alert-info">
                You have no upcoming appointments scheduled.
            </div>
        <?php else: ?>
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Pet</th>
                        <th>Veterinarian</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($upcomingAppointments as $appt): ?>
                        <tr>
                            <td><?= date('M j, Y', strtotime($appt['Date'])) ?></td>
                            <td><?= date('g:i A', strtotime($appt['Time'])) ?></td>
                            <td><?= htmlspecialchars($appt['pet_name']) ?></td>
                            <td><?= htmlspecialchars($appt['vet_name'] ?? 'Not assigned') ?></td>
                            <td><?= htmlspecialchars($appt['Reason']) ?></td>
                            <td class="status-<?= strtolower($appt['Status']) ?>">
                                <?= $appt['Status'] ?>
                            </td>
                            <td>
                                <a href="view_appointment.php?id=<?= $appt['Appt_ID'] ?>" class="btn btn-small">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="cancel_appointment.php?id=<?= $appt['Appt_ID'] ?>" 
                                   class="btn btn-small btn-danger"
                                   onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div class="appointment-actions">
            <a href="schedule_appointment.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Schedule New Appointment
            </a>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>

<?php 
require_once '../includes/footer.php';
?>