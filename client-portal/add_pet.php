<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';

// Ensure only clients can access
if ($_SESSION['role'] !== 'client') {
    header("Location: /login.php");
    exit();
}

// Get client's owner_id automatically
$stmt = $pdo->prepare("SELECT owner_id FROM Users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || !$user['owner_id']) {
    die("Your account is not properly linked to an owner record.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $species = $_POST['species'];
    $breed = trim($_POST['breed']);
    $age = $_POST['age'];

    // Validate inputs
    if (empty($name) || empty($species)) {
        $error = "Pet name and species are required";
    } elseif (!is_numeric($age) || $age < 0 || $age > 30) {
        $error = "Please enter a valid age (0-30)";
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO Pets (Name, Species, Breed, Age, Owner_ID)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $species, $breed, $age, $user['owner_id']]);
            
            $success = "Pet added successfully!";
            // Optional: Redirect to pets list
            // header("Location: my_pets.php");
            // exit();
        } catch (PDOException $e) {
            $error = "Error saving pet: " . $e->getMessage();
        }
    }
}

$page_title = "Add New Pet";
require_once '../includes/client_header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Pet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Add New Pet</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Pet Name*</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Species*</label>
                <select name="species" required>
                    <option value="">-- Select Species --</option>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Rabbit">Rabbit</option>
                    <option value="Bird">Bird</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Breed</label>
                <input type="text" name="breed">
            </div>
            
            <div class="form-group">
                <label>Age (years)</label>
                <input type="number" name="age" min="0" max="30">
            </div>
            
            <input type="hidden" name="owner_id" value="<?= $user['owner_id'] ?>">
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Pet
            </button>
            
            <a href="my_pets.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
        </form>
    </div>
</body>
</html>

<?php 
require_once '../includes/client_footer.php';
?>