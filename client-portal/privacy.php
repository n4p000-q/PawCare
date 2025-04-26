<?php
$page_title = "Privacy Policy";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/client_header.php';
?>

<div class="privacy-policy">
    <div class="policy-card">
        <h1><i class="fas fa-shield-alt"></i> Privacy Policy</h1>
        
        <div class="policy-section">
            <h2>1. Information We Collect</h2>
            <p>We collect personal information when you register with Paws & Care Veterinary System, including:</p>
            <ul>
                <li>Your name and contact details</li>
                <li>Your pet's medical history and records</li>
                <li>Appointment scheduling information</li>
                <li>Payment details for services rendered</li>
            </ul>
        </div>

        <div class="policy-section">
            <h2>2. How We Use Your Information</h2>
            <p>Your information is used to:</p>
            <ul>
                <li>Provide veterinary care services</li>
                <li>Schedule and manage appointments</li>
                <li>Process payments</li>
                <li>Send important health reminders</li>
                <li>Improve our services</li>
            </ul>
        </div>

        <div class="policy-section">
            <h2>3. Data Protection</h2>
            <p>We implement security measures including:</p>
            <ul>
                <li>Encryption of sensitive data</li>
                <li>Secure access controls</li>
                <li>Regular security audits</li>
                <li>Staff training on data protection</li>
            </ul>
        </div>

        <div class="policy-section">
            <h2>4. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access your personal data</li>
                <li>Request corrections to inaccurate information</li>
                <li>Request deletion of your data (where applicable)</li>
                <li>Object to certain processing activities</li>
            </ul>
        </div>

        <div class="policy-section">
            <h2>5. Policy Updates</h2>
            <p>This policy may be updated periodically. We will notify you of significant changes through your registered email address.</p>
            <p>Last updated: <?= date('F j, Y') ?></p>
        </div>
    </div>
</div>

<style>
    .privacy-policy {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .policy-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid var(--border);
    }

    .policy-card h1 {
        color: var(--primary);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .policy-section {
        margin-bottom: 2.5rem;
    }

    .policy-section h2 {
        color: var(--text);
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }

    .policy-section p {
        color: var(--text-light);
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .policy-section ul {
        padding-left: 1.5rem;
        color: var(--text-light);
    }

    .policy-section li {
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {
        .policy-card {
            padding: 1.5rem;
        }
        
        .privacy-policy {
            padding: 1rem;
        }
    }
</style>

<?php require_once '../includes/client_footer.php'; ?>