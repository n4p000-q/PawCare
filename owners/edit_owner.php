<?php
require_once '../config/db.php';

// Fetch owner data
if (isset($_GET['id'])) {
    $owner_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM Owners WHERE Owner_ID = ?");
    $stmt->execute([$owner_id]);
    $owner = $stmt->fetch();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_POST['owner_id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE Owners SET Name=?, Contact=?, Email=? WHERE Owner_ID=?");
        $stmt->execute([$name, $contact, $email, $owner_id]);
        header("Location: list_owners.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Owner</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Edit Owner: <?= htmlspecialchars($owner['Name']) ?></h1>
    <?php if (isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="owner_id" value="<?= $owner['Owner_ID'] ?>">
        
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($owner['Name']) ?>" required>

        <label>Contact:</label>
        <input type="text" name="contact" value="<?= htmlspecialchars($owner['Contact']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($owner['Email']) ?>" required>

        <button type="submit">Update Owner</button>
    </form>
    <a href="list_owners.php">Cancel</a>
</body>
</html>