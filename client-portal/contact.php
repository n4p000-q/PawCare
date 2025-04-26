<?php
$page_title = "Contact Us";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/client_header.php';
?>

<div class="contact-page">
    <div class="contact-card">
        <h1><i class="fas fa-envelope"></i> Contact Paws & Care</h1>
        
        <div class="contact-methods">
            <div class="contact-method">
                <div class="contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3>Phone</h3>
                <p>Call us during business hours:</p>
                <div class="contact-details">
                    <a href="tel:+26668517747">+266 6851 7747</a>
                    <a href="tel:+26662711525">+266 6271 1525</a>
                </div>
                <p>Hours: Mon-Fri 8:00 AM - 6:00 PM</p>
            </div>

            <div class="contact-method">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p>Send us a message anytime:</p>
                <div class="contact-details">
                    <a href="mailto:naponefsqheku@gmail.com">naponefsqheku@gmail.com</a>
                    <a href="mailto:kabelothesele81@gmail.com">kabelothesele81@gmail.com</a>
                </div>
                <p>We typically respond within 24 hours</p>
            </div>

            <div class="contact-method">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Visit Us</h3>
                <p>Our clinic location:</p>
                <div class="contact-details">
                    <p>123 Mafikeng Way</p>
                    <p>Scholarsticate</p>
                    <p>Roma 180, Lesotho</p>
                </div>
                <p>Open: Mon-Sat 8:00 AM - 5:00 PM</p>
            </div>
        </div>

        <div class="emergency-notice">
            <i class="fas fa-exclamation-triangle"></i>
            <p>For after-hours emergencies, please call our emergency line at <strong>+266 6851 7747</strong></p>
        </div>
    </div>
</div>

<style>
    .contact-page {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .contact-card {
        background: var(--card-bg);
        border-radius: var(--radius-lg);
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid var(--border);
    }

    .contact-card h1 {
        color: var(--primary);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .contact-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .contact-method {
        background: var(--light);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }

    .contact-method:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .contact-icon {
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

    .contact-method h3 {
        color: var(--text);
        margin-bottom: 1rem;
    }

    .contact-method p {
        color: var(--text-light);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .contact-details {
        margin: 1rem 0;
    }

    .contact-details a, .contact-details p {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--primary);
        text-decoration: none;
    }

    .contact-details a:hover {
        text-decoration: underline;
    }

    .emergency-notice {
        background: rgba(235, 59, 90, 0.1);
        border-left: 4px solid var(--danger);
        padding: 1rem;
        border-radius: 0 var(--radius-md) var(--radius-md) 0;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
    }

    .emergency-notice i {
        color: var(--danger);
        font-size: 1.5rem;
    }

    .emergency-notice p {
        margin: 0;
        color: var(--text);
    }

    .emergency-notice strong {
        color: var(--danger);
    }

    @media (max-width: 768px) {
        .contact-card {
            padding: 1.5rem;
        }
        
        .contact-page {
            padding: 1rem;
        }
        
        .contact-methods {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php require_once '../includes/client_footer.php'; ?>