<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Care | <?= $page_title ?? 'Vet Management' ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <img src="../assets/images/paw-logo.png" alt="Paws & Care Logo" height="40">
            <h1>Paws & Care</h1>
        </div>
        <nav>
            <ul class="nav-menu">
                <li><a href="../admin/dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="../pets/list_pets.php"><i class="fas fa-paw"></i> Patients</a></li>
                <li><a href="../appointments/list_appointments.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
                <li><a href="../reports/daily_appointments.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="content-wrapper">
        <!-- Back Button (conditionally shown) -->
        <?php if(!isset($hide_back_button)): ?>
            <a href="javascript:history.back()" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        <?php endif; ?>