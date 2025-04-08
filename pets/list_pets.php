<?php
require_once '../config/db.php';

// Fetch all pets with owner info
$stmt = $pdo->query("
    SELECT p.Pet_ID, p.Name AS PetName, p.Species, p.Breed, p.Age, o.Name AS OwnerName 
    FROM Pets p
    JOIN Owners o ON p.Owner_ID = o.Owner_ID
");
$pets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pet List</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Pet List</h1>
    <a href="add_pet.php">Add New Pet</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Species</th>
                <th>Breed</th>
                <th>Age</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pets as $pet): ?>
                <tr>
                    <td><?= $pet['Pet_ID'] ?></td>
                    <td><?= $pet['PetName'] ?></td>
                    <td><?= $pet['Species'] ?></td>
                    <td><?= $pet['Breed'] ?></td>
                    <td><?= $pet['Age'] ?></td>
                    <td><?= $pet['OwnerName'] ?></td>
                    <td>
                        <a href="edit_pet.php?id=<?= $pet['Pet_ID'] ?>" class="btn-edit">Edit</a>
                        <a href="delete_pet.php?id=<?= $pet['Pet_ID'] ?>" 
                            class="btn-delete" 
                            onclick="return confirm('Are you sure you want to delete this pet?')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>