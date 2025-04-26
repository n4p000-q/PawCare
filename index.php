<?php
session_start();
require_once 'config/db.php';

if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'client':
            header("Location: client-portal/index.php");
            break;
        case 'admin':
        case 'vet':
        case 'staff':
            header("Location: admin/dashboard.php");
            break;
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Care Veterinary Clinic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4B7BFF;
            --secondary: #FF6B6B;
            --accent: #4ECDC4;
            --text-light: #333333;
            --text-dark: #F0F0F0;
            --bg-light: #FFFFFF;
            --bg-dark: #1A1A2E;
            --card-light: rgba(255, 255, 255, 0.9);
            --card-dark: rgba(30, 30, 50, 0.9);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }

        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        /* Header & Navigation */
        header {
            width: 100%;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            z-index: 100;
        }

        body.dark-mode header {
            background: rgba(26, 26, 46, 0.9);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .logo {
            height: 50px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        nav ul {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        nav a {
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        body.dark-mode nav a {
            color: var(--text-dark);
        }

        nav a:hover {
            color: var(--primary);
        }

        /* Dark Mode Toggle */
        .dark-mode-toggle {
            background: rgba(0, 0, 0, 0.1);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            transition: all 0.3s ease;
        }

        body.dark-mode .dark-mode-toggle {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-dark);
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1);
        }

        /* Hero Section */
        .hero {
            width: 100%;
            padding: 8rem 2rem 4rem;
            text-align: center;
            background: linear-gradient(rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7)), url('assets/images/clinic-bg.jpg') no-repeat center/cover;
            margin-top: 60px;
        }

        body.dark-mode .hero {
            background: linear-gradient(rgba(26, 26, 46, 0.7), rgba(26, 26, 46, 0.7)), url('assets/images/clinic-bg-dark.jpg') no-repeat center/cover;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--text-light);
        }

        body.dark-mode .hero h1 {
            color: var(--text-dark);
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }

        /* Login Card */
        .login-card {
            background: var(--card-light);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            width: 90%;
            max-width: 400px;
            margin: 2rem auto;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .login-card {
            background: var(--card-dark);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .login-card h2 {
            margin-bottom: 1.5rem;
            color: var(--primary);
        }

        .btn-login {
            background: linear-gradient(45deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            font-size: 1rem;
            border-radius: 50px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(75, 123, 255, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(75, 123, 255, 0.4);
        }

        /* Features Section */
        .features {
            width: 100%;
            padding: 4rem 2rem;
            background: var(--bg-light);
            text-align: center;
        }

        body.dark-mode .features {
            background: var(--bg-dark);
        }

        .features h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            margin-bottom: 2rem;
            color: var(--primary);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--card-light);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        body.dark-mode .feature-card {
            background: var(--card-dark);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .feature-card h3 {
            margin-bottom: 0.5rem;
        }

        /* Footer */
        footer {
            width: 100%;
            padding: 2rem;
            text-align: center;
            background: var(--card-light);
            margin-top: auto;
        }

        body.dark-mode footer {
            background: var(--card-dark);
        }

        footer p {
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }
            nav ul {
                gap: 1rem;
            }
            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <img src="assets/images/templogo.png" alt="Paws & Care Logo" class="logo">
        <nav>
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="/client-portal/contact.php">Contact</a></li>
            </ul>
        </nav>
        <button class="dark-mode-toggle" id="darkModeToggle">
            <i class="fas fa-moon"></i>
        </button>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Welcome to Paws & Care</h1>
        <img src="assets/images/welcome.png" alt="Paws & Care Logo" style="height: 80px; margin-bottom: 1rem;">
        <p>Your trusted partner in pet health and wellness. We provide compassionate care for your furry family members.</p>
        <div class="login-card">
            <h2>Client & Staff Portal</h2>
            <a href="auth/login.php" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <p style="margin-top: 1rem;">New client? <a href="auth/register.php" style="color: var(--primary);">Register here</a></p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="services">
        <h2>Our Services</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-heartbeat"></i></div>
                <h3>Preventive Care</h3>
                <p>Regular checkups and vaccinations to keep your pet healthy.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bone"></i></div>
                <h3>Surgery</h3>
                <p>Advanced surgical procedures with compassionate care.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-tooth"></i></div>
                <h3>Dental Care</h3>
                <p>Professional dental cleaning and oral health services.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-home"></i></div>
                <h3>Boarding</h3>
                <p>Safe and comfortable boarding for your pets.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2023 Paws & Care Veterinary Clinic. All rights reserved.</p>
    </footer>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const icon = darkModeToggle.querySelector('i');
            if (body.classList.contains('dark-mode')) {
                icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                icon.classList.replace('fa-sun', 'fa-moon');
            }
        });
    </script>
</body>
</html>