<?php
require_once '../config/db.php';

// Fetch all owners
$owners = $pdo->query("SELECT * FROM Owners")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner List</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Owner List</h1>
    <a href="add_owner.php" class="btn-add">Add New Owner</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($owners as $owner): ?>
                <tr>
                    <td><?= $owner['Owner_ID'] ?></td>
                    <td><?= htmlspecialchars($owner['Name']) ?></td>
                    <td><?= htmlspecialchars($owner['Contact']) ?></td>
                    <td><?= htmlspecialchars($owner['Email']) ?></td>
                    <td>
                        <a href="edit_owner.php?id=<?= $owner['Owner_ID'] ?>" class="btn-edit">Edit</a>
                        <a href="delete_owner.php?id=<?= $owner['Owner_ID'] ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Delete this owner and all their pets?')">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>