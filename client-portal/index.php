<<?php

$page_title = "Client Dashboard";
require_once '../includes/client_header.php';
require_once '../config/db.php';
require_once '../includes/auth_check.php';

// Ensure only clients can access this page
if ($_SESSION['role'] !== 'client') {
    header("Location: /login.php");
    exit();
}

// Get client information
$stmt = $pdo->prepare("
    SELECT u.user_id, u.username, u.email, o.Name as owner_name, o.Contact, o.Email as owner_email
    FROM Users u
    LEFT JOIN Owners o ON u.owner_id = o.Owner_ID
    WHERE u.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$client = $stmt->fetch();

// Get client's pets
$pets = $pdo->prepare("
    SELECT p.Pet_ID, p.Name, p.Species, p.Breed, p.Age
    FROM Pets p
    WHERE p.Owner_ID = ?
    ORDER BY p.Name
");
$pets->execute([$client['owner_id']]);
$petList = $pets->fetchAll();

// Get upcoming appointments
$appointments = $pdo->prepare("
    SELECT a.Appt_ID, a.Date, a.Time, a.Reason, a.Status, v.Name as vet_name
    FROM Appointments a
    LEFT JOIN Vets v ON a.Vet_ID = v.Vet_ID
    JOIN Pets p ON a.Pet_ID = p.Pet_ID
    WHERE p.Owner_ID = ? AND a.Date >= CURDATE()
    ORDER BY a.Date, a.Time
    LIMIT 5
");
$appointments->execute([$client['owner_id']]);
$upcomingAppointments = $appointments->fetchAll();
?>

<style>
    /* Client Dashboard Styles */
    .client-dashboard {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .dashboard-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin: 15px 0;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .stat-item i {
        font-size: 2rem;
        color: var(--primary);
    }

    .pet-list {
        display: grid;
        gap: 10px;
        margin: 15px 0;
    }

    .pet-card {
        padding: 15px;
        border: 1px solid #eee;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 15px;
    }

    .btn-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        text-align: center;
    }

    .btn-action i {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }

    .appointment-table {
        width: 100%;
        margin: 15px 0;
        border-collapse: collapse;
    }

    .appointment-table th, 
    .appointment-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .appointment-table th {
        background-color: #f8f9fa;
    }
</style>

<div class="client-dashboard">
    <h1><i class="fas fa-user"></i> Welcome, <?= htmlspecialchars($client['owner_name'] ?? $client['username']) ?></h1>
    
    <div class="dashboard-grid">
        <!-- Quick Stats Section -->
        <div class="dashboard-card">
            <h2><i class="fas fa-chart-line"></i> Quick Stats</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fas fa-paw"></i>
                    <h3><?= count($petList) ?></h3>
                    <p>Registered Pets</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-calendar-alt"></i>
                    <h3><?= count($upcomingAppointments) ?></h3>
                    <p>Upcoming Appointments</p>
                </div>
            </div>
        </div>

        <!-- My Pets Section -->
        <div class="dashboard-card">
            <h2><i class="fas fa-paw"></i> My Pets</h2>
            <?php if (empty($petList)): ?>
                <p>You haven't registered any pets yet.</p>
            <?php else: ?>
                <div class="pet-list">
                    <?php foreach ($petList as $pet): ?>
                        <div class="pet-card">
                            <h3><?= htmlspecialchars($pet['Name']) ?></h3>
                            <p><?= htmlspecialchars($pet['Species']) ?> • <?= htmlspecialchars($pet['Breed']) ?></p>
                            <p>Age: <?= $pet['Age'] ?> years</p>
                            <a href="/client/pet_details.php?id=<?= $pet['Pet_ID'] ?>" class="btn btn-small">
                                View Details
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <a href="add_pet.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Pet
            </a>
        </div>

        <!-- Upcoming Appointments -->
        <div class="dashboard-card">
            <h2><i class="fas fa-calendar-check"></i> Upcoming Appointments</h2>
            <?php if (empty($upcomingAppointments)): ?>
                <p>No upcoming appointments scheduled.</p>
            <?php else: ?>
                <table class="appointment-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Pet</th>
                            <th>Reason</th>
                            <th>Vet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcomingAppointments as $apt): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($apt['Date'])) ?></td>
                                <td><?= date('g:i A', strtotime($apt['Time'])) ?></td>
                                <td><?= htmlspecialchars($apt['pet_name']) ?></td>
                                <td><?= htmlspecialchars($apt['Reason']) ?></td>
                                <td><?= htmlspecialchars($apt['vet_name'] ?? 'Not assigned') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <a href="schedule_appointment.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Schedule Appointment
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
            <div class="action-buttons">
                <a href="schedule_appointment.php" class="btn btn-action">
                    <i class="fas fa-calendar-plus"></i> New Appointment
                </a>
                <a href="../client-portal/my_pets.php" class="btn btn-action">
                    <i class="fas fa-paw"></i> Manage Pets
                </a>
                <a href="upcoming_appointments.php" class="btn btn-action">
                    <i class="fas fa-user-cog"></i> Upcoming Appointments
                </a>
                <a href="emergency.php" class="btn btn-action">
                    <i class="fas fa-prescription-bottle-alt"></i> Emergency
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/client_footer.php'; ?>