<?php
require_once '../config/db.php';

// Fetch appointment data
if (isset($_GET['id'])) {
    $appt_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM Appointments WHERE Appt_ID = ?");
    $stmt->execute([$appt_id]);
    $appointment = $stmt->fetch();
}

// Fetch pets and vets
$pets = $pdo->query("SELECT p.Pet_ID, p.Name, o.Name AS OwnerName FROM Pets p JOIN Owners o ON p.Owner_ID = o.Owner_ID")->fetchAll();
$vets = $pdo->query("SELECT * FROM Vets")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appt_id = $_POST['appt_id'];
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $status = $_POST['status'];

    try {
        $stmt = $pdo->prepare("UPDATE Appointments SET Pet_ID=?, Vet_ID=?, Date=?, Time=?, Reason=?, Status=? WHERE Appt_ID=?");
        $stmt->execute([$pet_id, $vet_id, $date, $time, $reason, $status, $appt_id]);
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
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Edit Appointment</h1>
    <?php if (isset($error)): ?>
        <div class="alert"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="appt_id" value="<?= $appointment['Appt_ID'] ?>">
        
        <label>Pet:</label>
        <select name="pet_id" required>
            <?php foreach ($pets as $pet): ?>
                <option value="<?= $pet['Pet_ID'] ?>" <?= $pet['Pet_ID'] == $appointment['Pet_ID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($pet['Name']) ?> (Owner: <?= htmlspecialchars($pet['OwnerName']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Vet:</label>
        <select name="vet_id" required>
            <?php foreach ($vets as $vet): ?>
                <option value="<?= $vet['Vet_ID'] ?>" <?= $vet['Vet_ID'] == $appointment['Vet_ID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($vet['Name']) ?> (<?= $vet['Specialization'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Date:</label>
        <input type="date" name="date" value="<?= $appointment['Date'] ?>" required>

        <label>Time:</label>
        <input type="time" name="time" value="<?= substr($appointment['Time'], 0, 5) ?>" required>

        <label>Reason:</label>
        <textarea name="reason" rows="3" required><?= htmlspecialchars($appointment['Reason']) ?></textarea>

        <label>Status:</label>
        <select name="status">
            <option value="Scheduled" <?= $appointment['Status'] == 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
            <option value="Completed" <?= $appointment['Status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Cancelled" <?= $appointment['Status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>

        <button type="submit">Update</button>
    </form>
</body>
</html>