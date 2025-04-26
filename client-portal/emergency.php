<?php
$page_title = "Emergency & Health Resources";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/client_header.php';
?>

<style>
    .emergency-container {
        max-width: 900px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .tab-header {
        display: flex;
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }
    
    .tab-btn {
        padding: 10px 20px;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        border-bottom: 3px solid transparent;
    }
    
    .tab-btn.active {
        border-bottom: 3px solid #dc3545;
        font-weight: bold;
        color: #dc3545;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .emergency-card {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #dc3545;
        background-color: #fff8f8;
    }
    
    .health-tip {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    
    .tip-category {
        display: inline-block;
        padding: 3px 8px;
        background: #4B7BFF;
        color: white;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-right: 10px;
    }
</style>

<div class="emergency-container">
    <h1><i class="fas fa-first-aid"></i> Pet Health Resources</h1>
    
    <div class="tab-header">
        <button class="tab-btn active" onclick="openTab('emergency')">
            <i class="fas fa-ambulance"></i> Emergency
        </button>
        <button class="tab-btn" onclick="openTab('healthtips')">
            <i class="fas fa-heartbeat"></i> Health Tips
        </button>
    </div>
    
    <!-- Emergency Tab Content -->
    <div id="emergency" class="tab-content active">
        <div class="emergency-card">
            <h3><i class="fas fa-phone-alt"></i> 24/7 Emergency Contacts</h3>
            <div class="contact-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 15px;">
                <div>
                    <h4>Emergency Veterinary Clinic</h4>
                    <p><strong>Paws & Claws Emergency</strong></p>
                    <p>📞 (555) 123-4567</p>
                    <p>📍 123 Emergency Lane, Your City</p>
                    <p>⏰ Open 24 hours</p>
                </div>
                <div>
                    <h4>Poison Control</h4>
                    <p><strong>ASPCA Animal Poison Control</strong></p>
                    <p>📞 (888) 426-4435</p>
                    <p>💵 $85 consultation fee may apply</p>
                </div>
            </div>
        </div>
        
        <div class="emergency-card">
            <h3><i class="fas fa-first-aid"></i> Emergency First Aid</h3>
            <ul style="list-style-type: none; padding-left: 0;">
                <li style="margin-bottom: 10px;">✅ <strong>Bleeding:</strong> Apply gentle pressure with clean cloth</li>
                <li style="margin-bottom: 10px;">✅ <strong>Choking:</strong> Check mouth for obstruction (be careful of bites)</li>
                <li style="margin-bottom: 10px;">❌ <strong>Poisoning:</strong> Do NOT induce vomiting unless instructed</li>
                <li style="margin-bottom: 10px;">🆘 <strong>Seizures:</strong> Clear area but don't restrain pet</li>
            </ul>
        </div>
        
        <div class="emergency-card">
            <h3><i class="fas fa-car-crash"></i> Transporting Injured Pets</h3>
            <p>1. Use a flat surface (board, blanket) as a stretcher</p>
            <p>2. Muzzle if necessary (unless vomiting or difficulty breathing)</p>
            <p>3. Call ahead to the emergency clinic</p>
        </div>
    </div>
    
    <!-- Health Tips Tab Content -->
    <div id="healthtips" class="tab-content">
        <?php
        // Simple database-driven health tips (could be hardcoded if preferred)
        $tips = $pdo->query("
            SELECT * FROM health_tips 
            ORDER BY category, tip_id DESC
            LIMIT 10
        ")->fetchAll();
        
        if (empty($tips)) {
            // Fallback hardcoded tips if no DB table exists
            $tips = [
                ['category' => 'prevention', 'title' => 'Flea Prevention', 'content' => 'Use vet-approved flea treatment monthly, especially in warm months.'],
                ['category' => 'nutrition', 'title' => 'Healthy Weight', 'content' => 'Measure food portions and limit treats to 10% of daily calories.'],
                ['category' => 'dental', 'title' => 'Dental Care', 'content' => 'Brush pet teeth weekly with pet-safe toothpaste.'],
                ['category' => 'seasonal', 'title' => 'Summer Safety', 'content' => 'Never leave pets in parked cars - temperatures rise dangerously fast.']
            ];
        }
        
        foreach ($tips as $tip): ?>
            <div class="health-tip">
                <span class="tip-category"><?= ucfirst($tip['category']) ?></span>
                <h4><?= htmlspecialchars($tip['title']) ?></h4>
                <p><?= htmlspecialchars($tip['content']) ?></p>
            </div>
        <?php endforeach; ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="#" class="btn btn-primary">
                <i class="fas fa-book-medical"></i> View More Tips
            </a>
        </div>
    </div>
</div>

<script>
    function openTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Deactivate all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Activate selected tab
        document.getElementById(tabName).classList.add('active');
        event.currentTarget.classList.add('active');
    }
</script>

<?php require_once '../includes/client_footer.php'; ?>