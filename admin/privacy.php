<?php
$page_title = "Privacy Policy";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/header.php';
?>

<div class="admin-privacy-policy">
    <div class="admin-policy-card">
        <h1><i class="fas fa-shield-alt"></i> Admin Privacy Policy</h1>
        
        <div class="admin-policy-section">
            <h2>1. Data Collection & Usage</h2>
            <p>As an administrator, you have access to:</p>
            <ul>
                <li>Client personal information (names, contact details)</li>
                <li>Pet medical records and treatment history</li>
                <li>Staff information and schedules</li>
                <li>Financial and billing records</li>
            </ul>
            <p class="admin-note"><strong>Note:</strong> This data must only be used for veterinary practice management purposes.</p>
        </div>

        <div class="admin-policy-section">
            <h2>2. Data Protection Responsibilities</h2>
            <p>Admin staff must:</p>
            <ul>
                <li>Keep login credentials secure</li>
                <li>Not share sensitive information outside the practice</li>
                <li>Access only data required for their role</li>
                <li>Report any suspected data breaches immediately</li>
            </ul>
        </div>

        <div class="admin-policy-section">
            <h2>3. System Security</h2>
            <p>The system implements:</p>
            <ul>
                <li>Role-based access controls</li>
                <li>Data encryption in transit and at rest</li>
                <li>Regular security audits</li>
                <li>Automatic logout after inactivity</li>
            </ul>
        </div>

        <div class="admin-policy-section">
            <h2>4. Compliance Requirements</h2>
            <p>All administrators must comply with:</p>
            <ul>
                <li>HIPAA regulations (where applicable)</li>
                <li>Local data protection laws</li>
                <li>Practice privacy policies</li>
                <li>Professional ethical standards</li>
            </ul>
        </div>

        <div class="admin-policy-section">
            <h2>5. Policy Updates</h2>
            <p>This policy is reviewed annually. Administrators will be notified of changes through system alerts.</p>
            <p class="admin-update">Last updated: <?= date('F j, Y') ?></p>
        </div>
    </div>
</div>

<style>
    .admin-privacy-policy {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .admin-policy-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid var(--border);
    }

    .admin-policy-card h1 {
        color: var(--primary);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 2px solid var(--primary);
        padding-bottom: 1rem;
    }

    .admin-policy-section {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px dashed var(--border);
    }

    .admin-policy-section:last-child {
        border-bottom: none;
    }

    .admin-policy-section h2 {
        color: var(--text);
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }

    .admin-policy-section p,
    .admin-policy-section li {
        color: var(--text-light);
        line-height: 1.6;
    }

    .admin-policy-section ul {
        padding-left: 1.5rem;
        margin: 1rem 0;
    }

    .admin-policy-section li {
        margin-bottom: 0.5rem;
    }

    .admin-note {
        background: rgba(75, 123, 236, 0.1);
        padding: 1rem;
        border-left: 4px solid var(--primary);
        margin-top: 1rem;
    }

    .admin-update {
        font-style: italic;
        color: var(--text-light);
        text-align: right;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .admin-policy-card {
            padding: 1.5rem;
        }
        
        .admin-privacy-policy {
            padding: 1rem;
        }
    }
</style>

<?php require_once '../includes/footer.php'; ?>