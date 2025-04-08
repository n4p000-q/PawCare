<?php
require_once '../config/db.php';

// Fetch pet data by ID
if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM Pets WHERE Pet_ID = ?");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch();

    if (!$pet) {
        die("Pet not found!");
    }
}

// Fetch all owners for dropdown
$owners = $pdo->query("SELECT Owner_ID, Name FROM Owners")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $owner_id = $_POST['owner_id'];

    try {
        $stmt = $pdo->prepare("UPDATE Pets SET Name=?, Species=?, Breed=?, Age=?, Owner_ID=? WHERE Pet_ID=?");
        $stmt->execute([$name, $species, $breed, $age, $owner_id, $pet_id]);
        header("Location: list_pets.php"); // Redirect after update
        exit();
    } catch (PDOException $e) {
        $error = "Error updating pet: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Edit Pet: <?= htmlspecialchars($pet['Name']) ?></h1>
    <?php if (isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="pet_id" value="<?= $pet['Pet_ID'] ?>">
        
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($pet['Name']) ?>" required>

        <label>Species:</label>
        <select name="species" required>
            <option value="Dog" <?= $pet['Species'] === 'Dog' ? 'selected' : '' ?>>Dog</option>
            <option value="Cat" <?= $pet['Species'] === 'Cat' ? 'selected' : '' ?>>Cat</option>
            <option value="Rabbit" <?= $pet['Species'] === 'Rabbit' ? 'selected' : '' ?>>Rabbit</option>
        </select>

        <label>Breed:</label>
        <input type="text" name="breed" value="<?= htmlspecialchars($pet['Breed']) ?>">

        <label>Age:</label>
        <input type="number" name="age" value="<?= $pet['Age'] ?>">

        <label>Owner:</label>
        <select name="owner_id" required>
            <?php foreach ($owners as $owner): ?>
                <option value="<?= $owner['Owner_ID'] ?>" 
                    <?= $owner['Owner_ID'] == $pet['Owner_ID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($owner['Name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Pet</button>
    </form>
    <a href="list_pets.php">Back to List</a>
</body>
</html>