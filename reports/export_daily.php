<?php
require_once '../config/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="daily_appointments_' . date('Y-m-d') . '.csv"');

$date = $_GET['date'] ?? date('Y-m-d');
$stmt = $pdo->prepare("SELECT [...] WHERE Date = ?");
$stmt->execute([$date]);

$file = fopen('php://output', 'w');
fputcsv($file, ['Time', 'Pet', 'Owner', 'Vet', 'Reason', 'Status']); // Headers

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($file, [
        date('h:i A', strtotime($row['Time'])),
        $row['Pet'],
        $row['Owner'],
        $row['Vet'] ?? 'Unassigned',
        $row['Reason'],
        $row['Status']
    ]);
}
fclose($file);
?>