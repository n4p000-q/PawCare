<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) session_start();

// Basic security check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: /login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Care | <?= $page_title ?? 'Client Portal' ?></title>
    <link rel="stylesheet" href="../assets/css/client-dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="client-header">
        <div class="logo">
            <img src="../assets/images/welcome.png" alt="Paws & Care Logo" height="40">
            <h1>Paws & Care Client Portal</h1>
        </div>
        <nav>
            <ul class="client-nav-menu">
                <li><a href="../client-portal/index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a></li>
                <li><a href="../client-portal/my_pets.php" class="<?= basename($_SERVER['PHP_SELF']) === 'pets.php' ? 'active' : '' ?>">
                    <i class="fas fa-paw"></i> My Pets
                </a></li>
                <li><a href="../client-portal/upcoming_appointments.php" class="<?= basename($_SERVER['PHP_SELF']) === 'upcoming_appointments.php' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i> Appointments
                </a></li>
<!--                <li><a href="../client/profile.php" class="<?= basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i> Profile
-->                </a></li>
                <li><a href="../auth/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a></li>
                <li>
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
            </ul>
        </nav>
    </header>

    <main class="client-content-wrapper">
        <!-- Back Button (conditionally shown) -->
        <?php if(!isset($hide_back_button)): ?>
            <a href="javascript:history.back()" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        <?php endif; ?>