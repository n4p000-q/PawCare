<?php
$page_title = "View Ticket";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: tickets.php");
    exit();
}

$ticket_id = $_GET['id'];

// Get ticket details
try {
    $stmt = $pdo->prepare("
        SELECT t.*, u.username 
        FROM support_tickets t
        JOIN Users u ON t.user_id = u.user_id
        WHERE t.ticket_id = ?
    ");
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        header("Location: tickets.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Error fetching ticket: " . $e->getMessage();
}

// Update status if needed
if (isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    try {
        $stmt = $pdo->prepare("UPDATE support_tickets SET status = ? WHERE ticket_id = ?");
        $stmt->execute([$new_status, $ticket_id]);
        $ticket['status'] = $new_status;
        $success = "Ticket status updated successfully";
    } catch (PDOException $e) {
        $error = "Error updating ticket: " . $e->getMessage();
    }
}

// Add response
if (isset($_POST['add_response'])) {
    $response = trim($_POST['response']);
    if (!empty($response)) {
        try {
            // In a real system, you'd store responses in a separate table
            // For now, we'll just update the ticket
            $stmt = $pdo->prepare("UPDATE support_tickets SET message = CONCAT(message, '\n\nADMIN RESPONSE:\n', ?) WHERE ticket_id = ?");
            $stmt->execute([$response, $ticket_id]);
            $success = "Response added successfully";
        } catch (PDOException $e) {
            $error = "Error adding response: " . $e->getMessage();
        }
    }
}
?>

<div class="ticket-view-page">
    <div class="ticket-view-card">
        <a href="tickets.php" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Tickets
        </a>
        
        <h1>
            <i class="fas fa-ticket-alt"></i> 
            Ticket #<?= $ticket['ticket_id'] ?>
            <span class="status-badge status-<?= $ticket['status'] ?>">
                <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
            </span>
        </h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="ticket-meta">
            <p><strong>Submitted by:</strong> <?= htmlspecialchars($ticket['username']) ?></p>
            <p><strong>Urgency:</strong> 
                <span class="urgency-badge urgency-<?= $ticket['urgency'] ?>">
                    <?= ucfirst($ticket['urgency']) ?>
                </span>
            </p>
            <p><strong>Created:</strong> <?= date('M j, Y g:i A', strtotime($ticket['created_at'])) ?></p>
            <p><strong>Last updated:</strong> <?= date('M j, Y g:i A', strtotime($ticket['updated_at'])) ?></p>
        </div>
        
        <div class="ticket-content">
            <h2><?= htmlspecialchars($ticket['subject']) ?></h2>
            <div class="ticket-message">
                <?= nl2br(htmlspecialchars($ticket['message'])) ?>
            </div>
        </div>
        
        <div class="ticket-actions">
            <form method="POST" class="status-form">
                <label for="status"><strong>Update Status:</strong></label>
                <select name="status" id="status">
                    <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                </select>
                <button type="submit" name="update_status" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
            </form>
            
            <form method="POST" class="response-form">
                <h3><i class="fas fa-reply"></i> Add Response</h3>
                <textarea name="response" rows="4" placeholder="Type your response here..." required></textarea>
                <button type="submit" name="add_response" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Response
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Add to your CSS -->
<style>
.ticket-view-page {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.ticket-view-card {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: 1px solid var(--border);
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    text-decoration: none;
    margin-bottom: 1.5rem;
}

.ticket-view-card h1 {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.ticket-meta {
    background: var(--light);
    padding: 1rem;
    border-radius: var(--radius-sm);
    margin-bottom: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.ticket-content {
    margin-bottom: 2rem;
}

.ticket-content h2 {
    color: var(--text);
    margin-bottom: 1rem;
}

.ticket-message {
    background: var(--light);
    padding: 1.5rem;
    border-radius: var(--radius-sm);
    white-space: pre-wrap;
}

.ticket-actions {
    margin-top: 2rem;
}

.status-form {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.status-form select {
    padding: 0.5rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}

.response-form textarea {
    width: 100%;
    padding: 1rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    margin-bottom: 1rem;
    background: var(--light);
}
</style>

<?php require_once '../includes/footer.php'; ?>