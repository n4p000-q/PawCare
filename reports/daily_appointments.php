<?php
require_once '../config/db.php';

// Default to today's date
$date = $_GET['date'] ?? date('Y-m-d');

// Fetch appointments for the selected date
$stmt = $pdo->prepare("
    SELECT a.Time, p.Name AS Pet, o.Name AS Owner, v.Name AS Vet, a.Reason, a.Status
    FROM Appointments a
    JOIN Pets p ON a.Pet_ID = p.Pet_ID
    JOIN Owners o ON p.Owner_ID = o.Owner_ID
    LEFT JOIN Vets v ON a.Vet_ID = v.Vet_ID
    WHERE a.Date = ?
    ORDER BY a.Time
");
$stmt->execute([$date]);
$appointments = $stmt->fetchAll();

$page_title = "Daily Appointments";
require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Appointments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Appointments for <?= date('F j, Y', strtotime($date)) ?></h1>
    
    <form method="GET" class="report-filter">
        <label>Select Date:</label>
        <input type="date" name="date" value="<?= $date ?>" max="<?= date('Y-m-d') ?>">
        <button type="submit">Generate</button>
    </form>

    <?php if (empty($appointments)): ?>
        <p>No appointments scheduled.</p>
    <?php else: ?>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Pet</th>
                    <th>Owner</th>
                    <th>Vet</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appt): ?>
                    <tr>
                        <td><?= date('h:i A', strtotime($appt['Time'])) ?></td>
                        <td><?= htmlspecialchars($appt['Pet']) ?></td>
                        <td><?= htmlspecialchars($appt['Owner']) ?></td>
                        <td><?= htmlspecialchars($appt['Vet'] ?? 'Unassigned') ?></td>
                        <td><?= htmlspecialchars($appt['Reason']) ?></td>
                        <td class="status-<?= strtolower($appt['Status']) ?>">
                            <?= $appt['Status'] ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

<?php 
require_once '../includes/footer.php';
?>