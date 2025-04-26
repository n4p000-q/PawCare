<?php
$page_title = "Contact Support";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

// Process ticket submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $urgency = $_POST['urgency'];
    $user_id = $_SESSION['user_id'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO support_tickets (user_id, subject, message, urgency) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $subject, $message, $urgency]);
        
        $success = "Your support ticket has been submitted successfully! Ticket ID: " . $pdo->lastInsertId();
    } catch (PDOException $e) {
        $error = "Error submitting ticket: " . $e->getMessage();
    }
}
?>

<div class="admin-contact-page">
    <div class="admin-contact-card">
        <h1><i class="fas fa-headset"></i> Admin Support</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="admin-contact-methods">
            <div class="admin-contact-method">
                <div class="admin-contact-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <h3>Technical Support</h3>
                <div class="admin-contact-details">
                    <p><i class="fas fa-envelope"></i> <a href="naponefsqheku@gmail.com">naponefsqheku@gmail.com</a></p>
                    <p><i class="fas fa-phone"></i> <a href="tel:+26668517747">+266 6851 7747</a></p>
                    <p>Available: Mon-Fri 8AM-5PM</p>
                </div>
            </div>

            <div class="admin-contact-method">
                <div class="admin-contact-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Admin Support</h3>
                <div class="admin-contact-details">
                    <p><i class="fas fa-envelope"></i> <a href="mailto:mafohlajames@gmail.com">mafohlajames@gmail.com.com</a></p>
                    <p><i class="fas fa-phone"></i> <a href="tel:+26663576902">+266 6357 6902</a></p>
                    <p>Available: Mon-Fri 9AM-4PM</p>
                </div>
            </div>

            <div class="admin-contact-method">
                <div class="admin-contact-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Emergency Support</h3>
                <div class="admin-contact-details">
                    <p><i class="fas fa-phone"></i> <a href="tel:+26668509717">+266 6850 9717 (24/7)</a></p>
                    <p>For critical system outages only</p>
                </div>
            </div>
        </div>

        <div class="admin-ticket-system">
            <h3><i class="fas fa-ticket-alt"></i> Submit Support Ticket</h3>
            <form class="admin-ticket-form" method="POST" action="">
                <div class="form-group">
                    <label for="ticket-subject">Subject *</label>
                    <input type="text" id="ticket-subject" name="subject" placeholder="Brief description of your issue" required>
                </div>
                <div class="form-group">
                    <label for="ticket-message">Message *</label>
                    <textarea id="ticket-message" name="message" rows="4" placeholder="Detailed description of your issue" required></textarea>
                </div>
                <div class="form-group">
                    <label for="ticket-urgency">Urgency *</label>
                    <select id="ticket-urgency" name="urgency" required>
                        <option value="low">Low (General question)</option>
                        <option value="medium" selected>Medium (Non-urgent issue)</option>
                        <option value="high">High (System problem affecting work)</option>
                        <option value="critical">Critical (System down)</option>
                    </select>
                </div>
                <button type="submit" name="submit_ticket" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Ticket
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Add this to your CSS -->

<style>
    .admin-contact-page {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .admin-contact-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid var(--border);
    }

    .admin-contact-card h1 {
        color: var(--primary);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 1rem;
    }

    .admin-contact-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .admin-contact-method {
        background: var(--light);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .admin-contact-method:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .admin-contact-icon {
        width: 50px;
        height: 50px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .admin-contact-method h3 {
        color: var(--text);
        margin-bottom: 1.5rem;
    }

    .admin-contact-details p {
        color: var(--text-light);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-contact-details a {
        color: var(--primary);
        text-decoration: none;
    }

    .admin-contact-details a:hover {
        text-decoration: underline;
    }

    .admin-ticket-system {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px dashed var(--border);
    }

    .admin-ticket-system h3 {
        color: var(--text);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-ticket-form {
        display: grid;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        color: var(--text);
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 0.8rem 1rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
        background: var(--light);
        color: var(--text);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    @media (max-width: 768px) {
        .admin-contact-card {
            padding: 1.5rem;
        }
        
        .admin-contact-page {
            padding: 1rem;
        }
        
        .admin-contact-methods {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php require_once '../includes/footer.php'; ?>