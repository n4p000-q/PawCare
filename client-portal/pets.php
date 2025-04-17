<?php
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

$pet_id = $_GET['id'] ?? 0;

// Verify pet belongs to logged-in client
$stmt = $pdo->prepare("
    SELECT p.* FROM Pets p
    JOIN Users u ON p.Owner_ID = u.owner_id
    WHERE p.Pet_ID = ? AND u.user_id = ?
");
$stmt->execute([$pet_id, $_SESSION['user_id']]);
$pet = $stmt->fetch();

if (!$pet) {
    header("Location: index.php");
    exit();
}

// Get medical records
$records = $pdo->prepare("
    SELECT * FROM Medical_Records
    WHERE Pet_ID = ?
    ORDER BY Record_ID DESC
");
$records->execute([$pet_id]);
?>

<div class="pet-details">
    <h2>
        <i class="fas fa-paw"></i>
        <?= htmlspecialchars($pet['Name']) ?>'s Health Records
    </h2>
    
    <div class="pet-info">
        <img src="../assets/images/pet-placeholder.jpg" alt="Pet Photo" class="pet-photo">
        
        <div class="pet-meta">
            <p><strong>Species:</strong> <?= $pet['Species'] ?></p>
            <p><strong>Breed:</strong> <?= $pet['Breed'] ?? 'Unknown' ?></p>
            <p><strong>Age:</strong> <?= $pet['Age'] ?? 'Unknown' ?> years</p>
        </div>
    </div>
    
    <h3><i class="fas fa-file-medical"></i> Medical History</h3>
    
    <?php if ($records->rowCount() === 0): ?>
        <p>No medical records found.</p>
    <?php else: ?>
        <table class="medical-records">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Treatment</th>
                    <th>Prescription</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($record = $records->fetch()): ?>
                    <tr>
                        <td><?= date('M j, Y', strtotime($record['created_at'])) ?></td>
                        <td><?= htmlspecialchars($record['Treatment']) ?></td>
                        <td><?= htmlspecialchars($record['Prescription']) ?></td>
                        <td><?= htmlspecialchars($record['Notes']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>