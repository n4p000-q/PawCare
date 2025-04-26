<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

require_once '../config/db.php';
//require_once '../includes/dashboard_header.php';
session_start();

$page_title = "Staff Dashboard";
require_once '../includes/auth_check.php';

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
    <link rel="stylesheet" href="../assets/css/style2(homepage).css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Dashboard-specific header styles */
        .dashboard-header {
            background: var(--header-bg);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-header .logo {
            display: flex;
            align-items: center;
        }
        
        .dashboard-header .logo img {
            height: 40px;
            margin-right: 15px;
        }
        
        .dashboard-header .logo h1 {
            color: var(--primary);
            margin: 0;
        }
        
        .dashboard-nav {
            display: flex;
            list-style: none;
            align-items: center;
        }
        
        .dashboard-nav li a {
            text-decoration: none;
            color: var(--text);
            padding: 0.5rem 1rem;
            margin: 0 5px;
            border-radius: 5px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .dashboard-nav li a:hover, 
        .dashboard-nav li a.active {
            background: var(--primary);
            color: white;
        }
        
        .dashboard-nav li a i {
            margin-right: 8px;
        }
        
        .dashboard-theme-toggle {
            background: transparent;
            border: none;
            color: var(--text);
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: 1rem;
            transition: transform 0.3s ease;
        }
        
        .dashboard-theme-toggle:hover {
            transform: scale(1.1);
        }
    </style>

</head>
<body>

    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="logo">
            <img src="../assets/images/welcome.png" alt="Paws & Care Logo">
            <h1>Paws & Care</h1>
        </div>
        <nav>
            <ul class="dashboard-nav">
                <li><a href="../admin/dashboard.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="../pets/list_pets.php"><i class="fas fa-paw"></i> Patients</a></li>
                <li><a href="../appointments/list_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
                <li><a href="../reports/daily_appointments.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="../admin/tickets.php"><i class="fas fa-ticket-alt"></i> Tickets</a></li>
                <li><a href="../auth/register_admin.php"><i class="fas fa-user-shield"></i> Admin</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php endif; ?>
                <li>
                    <button class="dashboard-theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Header --
    <header class="main-header">
        <div class="logo">
            <img src="../assets/images/templogo.png" alt="Paws & Care Logo">
            <h1>Welcome</h1>
        </div>
        </nav>
    </header> -->

    <!-- Hero Banner -->
    <section class="hero">
        <div class="hero-text">
            <h2>Compassionate Care for Every Pet</h2>
            <p>Efficiently manage your veterinary practice with our all-in-one system.</p>
            <a href="../appointments/add_appointment.php" class="btn-primary">Schedule Appointment</a>
        </div>
        <div class="hero-image">
            <img src="../assets/images/vet.png" alt="Happy Dog">
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="dashboard-stats">
        <div class="stat-card">
            <i class="fas fa-calendar-day"></i>
            <h3>Today's Appointments</h3>
            <p><?= $today_appointments ?></p>
            <a href="../appointments/list_appointments.php?date=<?= date('Y-m-d') ?>">View</a>
        </div>
        <div class="stat-card">
            <i class="fas fa-heartbeat"></i>
            <h3>Active Patients</h3>
            <p><?= $active_patients ?></p>
            <a href="../pets/list_pets.php">Manage</a>
        </div>
        <div class="stat-card">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Low Stock Items</h3>
            <p><?= $low_stock_items ?></p>
            <a href="../reports/inventory_report.php">Restock</a>
        </div>
        <div class="stat-card">
            <i class="fas fa-users-cog"></i>
            <h3>Staff Management</h3>
            <p><i class="fas fa-user-plus"></i></p>
            <a href="manage_staff.php">Manage</a>
        </div>
        <div class="stat-card">
                <i class="fas fa-user-friends"></i>
                <h3>Client Management</h3>
                <p><i class="fas fa-users"></i></p>
            <a href="../admin/manage_clients.php">Manage</a>
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

<?php require_once '../includes/footer.php'; ?>