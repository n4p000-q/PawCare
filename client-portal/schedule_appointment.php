<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';

// Ensure only clients can access
if ($_SESSION['role'] !== 'client') {
    header("Location: /login.php");
    exit();
}

// Get client's owner_id and pets
$stmt = $pdo->prepare("SELECT owner_id FROM Users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || !$user['owner_id']) {
    die("Your account is not properly linked to an owner record.");
}

// Get client's pets
$pets = $pdo->prepare("SELECT Pet_ID, Name FROM Pets WHERE Owner_ID = ? ORDER BY Name");
$pets->execute([$user['owner_id']]);
$clientPets = $pets->fetchAll();

// Get available vets
$vets = $pdo->query("SELECT Vet_ID, Name, Specialization FROM Vets ORDER BY Name");
$availableVets = $vets->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = trim($_POST['reason']);

    // Validate inputs
    if (empty($pet_id) || empty($date) || empty($time) || empty($reason)) {
        $error = "Please fill all required fields";
    } else {
        try {
            // Check if pet belongs to client
            $checkPet = $pdo->prepare("SELECT Pet_ID FROM Pets WHERE Pet_ID = ? AND Owner_ID = ?");
            $checkPet->execute([$pet_id, $user['owner_id']]);
            
            if ($checkPet->rowCount() === 0) {
                $error = "Invalid pet selection";
            } else {
                // Insert appointment
                $stmt = $pdo->prepare("
                    INSERT INTO Appointments (Pet_ID, Vet_ID, Date, Time, Reason, Status)
                    VALUES (?, ?, ?, ?, ?, 'Scheduled')
                ");
                $stmt->execute([$pet_id, $vet_id, $date, $time, $reason]);
                
                $success = "Appointment scheduled successfully!";
                // Optional: Redirect to appointments list
                // header("Location: my_appointments.php");
                // exit();
            }
        } catch (PDOException $e) {
            $error = "Error scheduling appointment: " . $e->getMessage();
        }
    }
}

$page_title = "Schedule Appointment";
require_once '../includes/client_header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Schedule Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .appointment-form {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .date-time-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <div class="appointment-form">
        <h1><i class="fas fa-calendar-plus"></i> Schedule New Appointment</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Select Pet*</label>
                <select name="pet_id" required>
                    <option value="">-- Choose Your Pet --</option>
                    <?php foreach ($clientPets as $pet): ?>
                        <option value="<?= $pet['Pet_ID'] ?>">
                            <?= htmlspecialchars($pet['Name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Select Veterinarian*</label>
                <select name="vet_id" required>
                    <option value="">-- Choose Veterinarian --</option>
                    <?php foreach ($availableVets as $vet): ?>
                        <option value="<?= $vet['Vet_ID'] ?>">
                            <?= htmlspecialchars($vet['Name']) ?> (<?= $vet['Specialization'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="date-time-group">
                <div class="form-group">
                    <label>Date*</label>
                    <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Time*</label>
                    <input type="time" name="time" min="09:00" max="17:00" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Reason for Visit*</label>
                <textarea name="reason" rows="3" required></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i> Schedule Appointment
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </form>
    </div>
</body>
</html>

<?php 
require_once '../includes/client_footer.php';
?>