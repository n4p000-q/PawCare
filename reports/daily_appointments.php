<?php
require_once '../config/db.php';

// Determine which report to show
$report = $_GET['report'] ?? 'appointments';

// Set page title based on report
$page_title = ($report === 'inventory') ? "Inventory Report" : "Daily Appointments";
require_once '../includes/header.php';

// Handle appointments report
if ($report === 'appointments') {
    // Default to today's date
    $date = $_GET['date'] ?? date('Y-m-d');

    // Fetch appointments for the selected date
    $stmt = $pdo->prepare("
        SELECT a.Time, p.Name AS Pet, o.Name AS Owner, v.Name AS Vet, a.Reason, a.Status
        FROM Appointments a
        JOIN Pets p ON a.Pet_ID = p.Pet_ID
        JOIN Owners o ON p.Owner_ID = o.Owner_ID
        LEFT JOIN Vets v ON a.Vet_ID = v.Vet_ID
        WHERE a.Date = ?
        ORDER BY a.Time
    ");
    $stmt->execute([$date]);
    $appointments = $stmt->fetchAll();
} 
// Handle inventory report
elseif ($report === 'inventory') {
    // Fetch inventory data
    $stmt = $pdo->query("
        SELECT p.Product_ID, p.Name, p.Category, p.Price, i.Stock_Level, i.Supplier
        FROM Products p
        JOIN Inventory i ON p.Product_ID = i.Product_ID
        ORDER BY p.Category, p.Name
    ");
    $inventory = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .report-nav {
            margin-bottom: 20px;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
        }
        .report-nav a {
            margin-right: 15px;
            padding: 5px 10px;
            text-decoration: none;
            background: #e0e0e0;
            border-radius: 3px;
        }
        .report-nav a.active {
            background: #4CAF50;
            color: white;
        }
        .status-pending { color: orange; }
        .status-completed { color: green; }
        .status-cancelled { color: red; }
        .text-danger { color: red; }
        .text-success { color: green; }
    </style>
</head>
<body>
    <div class="report-nav">
        <a href="?report=appointments" class="<?= $report === 'appointments' ? 'active' : '' ?>">Appointments</a>
        <a href="?report=inventory" class="<?= $report === 'inventory' ? 'active' : '' ?>">Inventory</a>
    </div>

    <?php if ($report === 'appointments'): ?>
        <h1>Appointments for <?= date('F j, Y', strtotime($date)) ?></h1>
        
        <form method="GET" class="report-filter">
            <input type="hidden" name="report" value="appointments">
            <label>Select Date:</label>
            <input type="date" name="date" value="<?= $date ?>" max="<?= date('Y-m-d') ?>">
            <button type="submit">Generate</button>
        </form>

        <?php if (empty($appointments)): ?>
            <p>No appointments scheduled.</p>
        <?php else: ?>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Pet</th>
                        <th>Owner</th>
                        <th>Vet</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appt): ?>
                        <tr>
                            <td><?= date('h:i A', strtotime($appt['Time'])) ?></td>
                            <td><?= htmlspecialchars($appt['Pet']) ?></td>
                            <td><?= htmlspecialchars($appt['Owner']) ?></td>
                            <td><?= htmlspecialchars($appt['Vet'] ?? 'Unassigned') ?></td>
                            <td><?= htmlspecialchars($appt['Reason']) ?></td>
                            <td class="status-<?= strtolower($appt['Status']) ?>">
                                <?= $appt['Status'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    <?php elseif ($report === 'inventory'): ?>
        <h1>Inventory Status</h1>
        
        <table class="report-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>In Stock</th>
                    <th>Supplier</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['Name']) ?></td>
                        <td><?= htmlspecialchars($item['Category']) ?></td>
                        <td>$<?= number_format($item['Price'], 2) ?></td>
                        <td><?= $item['Stock_Level'] ?></td>
                        <td><?= htmlspecialchars($item['Supplier']) ?></td>
                        <td class="<?= $item['Stock_Level'] < 10 ? 'text-danger' : 'text-success' ?>">
                            <?= $item['Stock_Level'] < 10 ? 'Low Stock' : 'In Stock' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

<?php 
require_once '../includes/footer.php';
?>