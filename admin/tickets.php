<?php
$page_title = "Support Tickets";
require_once '../config/db.php';
require_once '../includes/auth_check.php';
require_once '../includes/header.php';

// Check if admin is resolving a ticket
if (isset($_GET['resolve'])) {
    $ticket_id = $_GET['resolve'];
    try {
        $stmt = $pdo->prepare("UPDATE support_tickets SET status = 'resolved' WHERE ticket_id = ?");
        $stmt->execute([$ticket_id]);
        $success = "Ticket #$ticket_id marked as resolved";
    } catch (PDOException $e) {
        $error = "Error updating ticket: " . $e->getMessage();
    }
}

// Get all tickets
try {
    $stmt = $pdo->prepare("
        SELECT t.*, u.username 
        FROM support_tickets t
        JOIN Users u ON t.user_id = u.user_id
        ORDER BY 
            CASE WHEN status = 'open' THEN 0
                 WHEN status = 'in_progress' THEN 1
                 ELSE 2 END,
            CASE urgency
                WHEN 'critical' THEN 0
                WHEN 'high' THEN 1
                WHEN 'medium' THEN 2
                ELSE 3 END,
            created_at DESC
    ");
    $stmt->execute();
    $tickets = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching tickets: " . $e->getMessage();
}
?>

<div class="admin-tickets-page">
    <div class="admin-tickets-card">
        <h1><i class="fas fa-ticket-alt"></i> Support Tickets</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="ticket-filters">
            <a href="?status=all" class="btn btn-filter">All Tickets</a>
            <a href="?status=open" class="btn btn-filter">Open</a>
            <a href="?status=in_progress" class="btn btn-filter">In Progress</a>
            <a href="?status=resolved" class="btn btn-filter">Resolved</a>
        </div>
        
        <div class="tickets-list">
            <?php if (empty($tickets)): ?>
                <p>No support tickets found.</p>
            <?php else: ?>
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Submitted By</th>
                            <th>Urgency</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr class="ticket-<?= $ticket['status'] ?>">
                                <td>#<?= $ticket['ticket_id'] ?></td>
                                <td>
                                    <a href="ticket_view.php?id=<?= $ticket['ticket_id'] ?>">
                                        <?= htmlspecialchars($ticket['subject']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($ticket['username']) ?></td>
                                <td>
                                    <span class="urgency-badge urgency-<?= $ticket['urgency'] ?>">
                                        <?= ucfirst($ticket['urgency']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $ticket['status'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                                    </span>
                                </td>
                                <td><?= date('M j, Y g:i A', strtotime($ticket['created_at'])) ?></td>
                                <td>
                                    <?php if ($ticket['status'] !== 'resolved'): ?>
                                        <a href="?resolve=<?= $ticket['ticket_id'] ?>" class="btn btn-sm btn-resolve">
                                            <i class="fas fa-check"></i> Resolve
                                        </a>
                                    <?php endif; ?>
                                    <a href="ticket_view.php?id=<?= $ticket['ticket_id'] ?>" class="btn btn-sm btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add to your CSS -->
<style>
.admin-tickets-page {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.admin-tickets-card {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border: 1px solid var(--border);
}

.ticket-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.btn-filter {
    background: var(--light);
    color: var(--text);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    background: var(--primary);
    color: white;
}

.tickets-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.tickets-table th {
    background: var(--light);
    padding: 1rem;
    text-align: left;
}

.tickets-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
}

.tickets-table tr:hover {
    background: rgba(75, 123, 236, 0.05);
}

.urgency-badge, .status-badge {
    padding: 0.3rem 0.6rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 500;
}

.urgency-critical {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.urgency-high {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.urgency-medium {
    background: rgba(23, 162, 184, 0.2);
    color: #17a2b8;
}

.urgency-low {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.status-open {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.status-in_progress {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.status-resolved {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-resolve {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.btn-resolve:hover {
    background: rgba(40, 167, 69, 0.2);
}

.btn-view {
    background: rgba(75, 123, 236, 0.1);
    color: var(--primary);
    border: 1px solid rgba(75, 123, 236, 0.3);
}

.btn-view:hover {
    background: rgba(75, 123, 236, 0.2);
}
</style>

<?php require_once '../includes/footer.php'; ?>