<?php
require_once '../config/db.php';

// Fetch all owners for dropdown
$owners = $pdo->query("SELECT Owner_ID, Name FROM Owners")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $owner_id = $_POST['owner_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Pets (Name, Species, Breed, Age, Owner_ID) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $species, $breed, $age, $owner_id]);
        header("Location: list_pets.php"); // Redirect after success
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Pet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Add New Pet</h1>
    <?php if (isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Species:</label>
        <select name="species" required>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Rabbit">Rabbit</option>
        </select>

        <label>Breed:</label>
        <input type="text" name="breed">

        <label>Age:</label>
        <input type="number" name="age">

        <label>Owner:</label>
        <select name="owner_id" required>
            <?php foreach ($owners as $owner): ?>
                <option value="<?= $owner['Owner_ID'] ?>"><?= $owner['Name'] ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save Pet</button>
    </form>
</body>
</html>