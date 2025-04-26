<?php
$page_title = "Pet Details";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/client_header.php';

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
$medicalRecords = $records->fetchAll();
?>

<style>
    /* Pet Details Styles */
    .pet-details {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .pet-info {
        display: flex;
        gap: 20px;
        margin: 20px 0;
        align-items: center;
    }

    .pet-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #eee;
    }

    .pet-meta p {
        margin: 8px 0;
        font-size: 1.1rem;
    }

    .medical-records {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    .medical-records th, 
    .medical-records td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .medical-records th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .medical-records tr:hover {
        background-color: #f8f9fa;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #4B7BFF;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .no-records {
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        border-radius: 8px;
        margin: 20px 0;
    }
</style>

<div class="pet-details">
    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    
    <h2>
        <i class="fas fa-paw"></i>
        <?= htmlspecialchars($pet['Name']) ?>'s Health Records
    </h2>
    
    <div class="pet-info">
        <img src="../assets/images/pet-placeholder.jpg" alt="Pet Photo" class="pet-photo">
        
        <div class="pet-meta">
            <p><strong>Species:</strong> <?= htmlspecialchars($pet['Species']) ?></p>
            <p><strong>Breed:</strong> <?= htmlspecialchars($pet['Breed'] ?? 'Unknown') ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($pet['Age'] ?? 'Unknown') ?> years</p>
        </div>
    </div>
    
    <h3><i class="fas fa-file-medical"></i> Medical History</h3>
    
    <?php if (empty($medicalRecords)): ?>
        <div class="no-records">
            <p>No medical records found for <?= htmlspecialchars($pet['Name']) ?>.</p>
        </div>
    <?php else: ?>
        <table class="medical-records">
            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>Treatment</th>
                    <th>Prescription</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicalRecords as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['Record_ID']) ?></td>
                        <td><?= htmlspecialchars($record['Treatment']) ?></td>
                        <td><?= htmlspecialchars($record['Prescription'] ?? 'None') ?></td>
                        <td><?= htmlspecialchars($record['Notes'] ?? 'None') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once '../includes/client_footer.php'; ?>