<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Owners (Name, Contact, Email) VALUES (?, ?, ?)");
        $stmt->execute([$name, $contact, $email]);
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
    <title>Add Owner</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Add New Owner</h1>
    <?php if (isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Contact:</label>
        <input type="text" name="contact" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <button type="submit">Save Owner</button>
    </form>
    <a href="list_owners.php">Cancel</a>
</body>
</html>