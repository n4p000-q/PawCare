<?php
$page_title = "My Pets";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/client_header.php';

// Get client information to get owner_id
$stmt = $pdo->prepare("
    SELECT u.owner_id 
    FROM Users u
    WHERE u.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$client = $stmt->fetch();

// Get all pets for this client
$pets = $pdo->prepare("
    SELECT p.Pet_ID, p.Name, p.Species, p.Breed, p.Age
    FROM Pets p
    WHERE p.Owner_ID = ?
    ORDER BY p.Name
");
$pets->execute([$client['owner_id']]);
$petList = $pets->fetchAll();
?>

<style>
    .my-pets {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .pet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }
    
    .pet-card {
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        transition: transform 0.2s;
    }
    
    .pet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .pet-actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
    
    .no-pets {
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
        border-radius: 8px;
    }
</style>

<div class="my-pets">
    <h1><i class="fas fa-paw"></i> My Pets</h1>
    
    <a href="add_pet.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Pet
    </a>
    
    <?php if (empty($petList)): ?>
        <div class="no-pets">
            <p>You haven't registered any pets yet.</p>
        </div>
    <?php else: ?>
        <div class="pet-grid">
            <?php foreach ($petList as $pet): ?>
                <div class="pet-card">
                    <h3><?= htmlspecialchars($pet['Name']) ?></h3>
                    <p><?= htmlspecialchars($pet['Species']) ?> • <?= htmlspecialchars($pet['Breed']) ?></p>
                    <p>Age: <?= $pet['Age'] ?> years</p>
                    <div class="pet-actions">
                        <a href="pets.php?id=<?= $pet['Pet_ID'] ?>" class="btn btn-small">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="edit_pet.php?id=<?= $pet['Pet_ID'] ?>" class="btn btn-small">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/client_footer.php'; ?>