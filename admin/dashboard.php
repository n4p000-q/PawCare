<?php
require_once 'config/db.php';
session_start();

$page_title = "Staff Dashboard";
//reire_once '../includes/auth_check.php'; // Restrict to staff roles
require_once '../includes/header.php';

// Fetch stats for dashboard
$today_appointments = $pdo->query("SELECT COUNT(*) FROM Appointments WHERE Date = CURDATE()")->fetchColumn();
$active_patients = $pdo->query("SELECT COUNT(DISTINCT Pet_ID) FROM Medical_Records")->fetchColumn();
$low_stock_items = $pdo->query("SELECT COUNT(*) FROM Inventory WHERE Stock_Level < 10")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Care - Vet Management System</title>
    <link rel="stylesheet" href="assets/css/style2(homepage).css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="logo">
            <img src="assets/images/paw-logo.png" alt="Paws & Care Logo">
            <h1>Paws & Care</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="pets/list_pets.php"><i class="fas fa-paw"></i> Patients</a></li>
                <li><a href="appointments/list_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
                <li><a href="reports/daily_appointments.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Banner -->
    <section class="hero">
        <div class="hero-text">
            <h2>Compassionate Care for Every Pet</h2>
            <p>Efficiently manage your veterinary practice with our all-in-one system.</p>
            <a href="appointments/add_appointment.php" class="btn-primary">Schedule Appointment</a>
        </div>
        <div class="hero-image">
            <img src="assets/images/hero-dog.png" alt="Happy Dog">
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="dashboard-stats">
        <div class="stat-card">
            <i class="fas fa-calendar-day"></i>
            <h3>Today's Appointments</h3>
            <p><?= $today_appointments ?></p>
            <a href="appointments/list_appointments.php?date=<?= date('Y-m-d') ?>">View</a>
        </div>
        <div class="stat-card">
            <i class="fas fa-heartbeat"></i>
            <h3>Active Patients</h3>
            <p><?= $active_patients ?></p>
            <a href="pets/list_pets.php">Manage</a>
        </div>
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Low Stock Items</h3>
            <p><?= $low_stock_items ?></p>
            <a href="reports/inventory_report.php">Restock</a>
        </div>
    </section>

    <!-- Recent Activity -->
    <section class="recent-activity">
        <h2><i class="fas fa-history"></i> Recent Activity</h2>
        <div class="activity-list">
            <?php
            $activity = $pdo->query("
                (SELECT 'Appointment' AS type, a.Date, CONCAT('With ', p.Name) AS description
                FROM Appointments a
                JOIN Pets p ON a.Pet_ID = p.Pet_ID
                ORDER BY a.Date DESC LIMIT 3)
                UNION
                (SELECT 'Treatment' AS type, m.Record_ID AS Date, CONCAT('For ', p.Name) AS description
                FROM Medical_Records m
                JOIN Pets p ON m.Pet_ID = p.Pet_ID
                ORDER BY m.Record_ID DESC LIMIT 2)
                ORDER BY Date DESC LIMIT 5
            ")->fetchAll();

            foreach ($activity as $item):
                $icon = ($item['type'] == 'Appointment') ? 'fa-calendar-check' : 'fa-medkit';
            ?>
                <div class="activity-item">
                    <i class="fas <?= $icon ?>"></i>
                    <div>
                        <h4><?= $item['type'] ?></h4>
                        <p><?= $item['description'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y') ?> Paws & Care Vet Management System</p>
    </footer>
</body>
</html>