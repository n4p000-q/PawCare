<?php
require_once '../config/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($username) || empty($password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        try {
            $pdo->beginTransaction();
            
            // Check if username/email exists
            $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Username or email already exists";
            } else {
                // Create new owner
                $stmt = $pdo->prepare("INSERT INTO Owners (Name, Contact, Email) VALUES (?, ?, ?)");
                $stmt->execute([$name, $phone, $email]);
                $owner_id = $pdo->lastInsertId();
                
                // Create new user account
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    INSERT INTO Users (username, password, email, role, owner_id)
                    VALUES (?, ?, ?, 'client', ?)
                ");
                $stmt->execute([$username, $hashed_password, $email, $owner_id]);
                
                $pdo->commit();
                $success = "Registration successful! You can now login.";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Registration error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Registration | Paws & Care</title>
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

        /* Header */
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

        /* Registration Container */
        .registration-container {
            width: 100%;
            padding: 8rem 2rem 4rem;
            text-align: center;
            margin-top: 60px;
        }

        .registration-card {
            background: var(--card-light);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .registration-card {
            background: var(--card-dark);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .registration-card h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        body.dark-mode .form-group input {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--text-dark);
        }

        .btn-register {
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
            width: 100%;
            justify-content: center;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(75, 123, 255, 0.4);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
        }

        .alert-danger {
            background-color: rgba(255, 107, 107, 0.2);
            border: 1px solid var(--secondary);
        }

        .alert-success {
            background-color: rgba(78, 205, 196, 0.2);
            border: 1px solid var(--accent);
        }

        .login-link {
            margin-top: 1.5rem;
            display: block;
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
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

        @media (max-width: 768px) {
            .registration-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="../index.php">
            <img src="../assets/images/welcome.png" alt="Paws & Care Logo" class="logo">
        </a>
        <button class="dark-mode-toggle" id="darkModeToggle">
            <i class="fas fa-moon"></i>
        </button>
    </header>

    <!-- Registration Form -->
    <div class="registration-container">
        <div class="registration-card">
            <h1>Create Your Account</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password (min 8 characters)</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        // Check for saved theme preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            body.classList.add('dark-mode');
            darkModeToggle.querySelector('i').classList.replace('fa-moon', 'fa-sun');
        }

        darkModeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const icon = darkModeToggle.querySelector('i');
            
            if (body.classList.contains('dark-mode')) {
                icon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                icon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    </script>
</body>
</html>