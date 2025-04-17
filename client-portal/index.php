<?php
require_once '../includes/auth_check.php'; // Ensures client is logged in
require_once '../includes/header.php';

$client_id = $_SESSION['user_id'];
?>

<div class="client-dashboard">
    <h2><i class="fas fa-paw"></i> My Pets</h2>
    
    <?php
    $pets = $pdo->prepare("
        SELECT p.* FROM Pets p
        JOIN Users u ON p.Owner_ID = u.owner_id
        WHERE u.user_id = ?
    ");
    $pets->execute([$client_id]);
    ?>

    <div class="pet-cards">
        <?php while ($pet = $pets->fetch()): ?>
            <div class="pet-card">
                <h3><?= htmlspecialchars($pet['Name']) ?></h3>
                <p>Species: <?= $pet['Species'] ?></p>
                <a href="pets.php?id=<?= $pet['Pet_ID'] ?>" class="btn btn-small">
                    View Details
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>