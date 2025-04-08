<?php
require_once '../config/db.php';

// Fetch pets and vets for dropdowns
$pets = $pdo->query("SELECT p.Pet_ID, p.Name, o.Name AS OwnerName FROM Pets p JOIN Owners o ON p.Owner_ID = o.Owner_ID")->fetchAll();
$vets = $pdo->query("SELECT * FROM Vets")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Appointments (Pet_ID, Vet_ID, Date, Time, Reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$pet_id, $vet_id, $date, $time, $reason]);
        header("Location: list_appointments.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Schedule Appointment</h1>
    <?php if (isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Pet:</label>
        <select name="pet_id" required>
            <?php foreach ($pets as $pet): ?>
                <option value="<?= $pet['Pet_ID'] ?>">
                    <?= htmlspecialchars($pet['Name']) ?> (Owner: <?= htmlspecialchars($pet['OwnerName']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Vet:</label>
        <select name="vet_id" required>
            <?php foreach ($vets as $vet): ?>
                <option value="<?= $vet['Vet_ID'] ?>">
                    <?= htmlspecialchars($vet['Name']) ?> (<?= $vet['Specialization'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Date:</label>
        <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>

        <label>Time:</label>
        <input type="time" name="time" min="09:00" max="17:00" required>

        <label>Reason:</label>
        <textarea name="reason" rows="3" required></textarea>

        <button type="submit">Schedule</button>
    </form>
</body>
</html>