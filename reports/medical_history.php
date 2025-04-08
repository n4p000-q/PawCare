<?php
require_once '../config/db.php';

// Get pet ID from URL
$pet_id = $_GET['pet_id'] ?? null;

// Fetch pet details
if ($pet_id) {
    $pet_stmt = $pdo->prepare("SELECT p.Name, p.Species, o.Name AS Owner FROM Pets p JOIN Owners o ON p.Owner_ID = o.Owner_ID WHERE p.Pet_ID = ?");
    $pet_stmt->execute([$pet_id]);
    $pet = $pet_stmt->fetch();
}

// Fetch medical records
$records = [];
if ($pet_id) {
    $stmt = $pdo->prepare("SELECT Treatment, Prescription, Notes FROM Medical_Records WHERE Pet_ID = ? ORDER BY Record_ID DESC");
    $stmt->execute([$pet_id]);
    $records = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical History</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Medical History Report</h1>
    
    <form method="GET" class="report-filter">
        <label>Select Pet:</label>
        <select name="pet_id" required>
            <option value="">-- Choose Pet --</option>
            <?php
            $pets = $pdo->query("SELECT p.Pet_ID, p.Name, o.Name AS Owner FROM Pets p JOIN Owners o ON p.Owner_ID = o.Owner_ID")->fetchAll();
            foreach ($pets as $p): ?>
                <option value="<?= $p['Pet_ID'] ?>" <?= $pet_id == $p['Pet_ID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['Name']) ?> (Owner: <?= htmlspecialchars($p['Owner']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Generate</button>
    </form>

    <?php if ($pet_id && $pet): ?>
        <div class="pet-info">
            <h2><?= htmlspecialchars($pet['Name']) ?> (<?= $pet['Species'] ?>)</h2>
            <p>Owner: <?= htmlspecialchars($pet['Owner']) ?></p>
        </div>

        <?php if (empty($records)): ?>
            <p>No medical records found.</p>
        <?php else: ?>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Treatment</th>
                        <th>Prescription</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['Treatment']) ?></td>
                            <td><?= htmlspecialchars($record['Prescription']) ?></td>
                            <td><?= htmlspecialchars($record['Notes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>