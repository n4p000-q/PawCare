<?php
require_once '../config/db.php';

// Default date range (last 30 days)
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-30 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Fetch sales data
$stmt = $pdo->prepare("
    SELECT o.Order_ID, o.Date, o.Total, c.Name AS Customer,
           GROUP_CONCAT(p.Name SEPARATOR ', ') AS Products
    FROM Orders o
    JOIN Owners c ON o.Customer_ID = c.Owner_ID
    JOIN Order_Details od ON o.Order_ID = od.Order_ID
    JOIN Products p ON od.Product_ID = p.Product_ID
    WHERE o.Date BETWEEN ? AND ?
    GROUP BY o.Order_ID
    ORDER BY o.Date DESC
");
$stmt->execute([$start_date, $end_date]);
$sales = $stmt->fetchAll();

// Calculate total revenue
$total_stmt = $pdo->prepare("SELECT SUM(Total) AS Revenue FROM Orders WHERE Date BETWEEN ? AND ?");
$total_stmt->execute([$start_date, $end_date]);
$total = $total_stmt->fetch();

$page_title = "Sales Report";
require_once '../includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Sales Summary</h1>
    
    <form method="GET" class="report-filter">
        <label>From:</label>
        <input type="date" name="start_date" value="<?= $start_date ?>">
        
        <label>To:</label>
        <input type="date" name="end_date" value="<?= $end_date ?>" max="<?= date('Y-m-d') ?>">
        
        <button type="submit">Generate</button>
    </form>

    <div class="report-summary">
        <p>Period: <?= date('M j, Y', strtotime($start_date)) ?> to <?= date('M j, Y', strtotime($end_date)) ?></p>
        <p class="highlight">Total Revenue: $<?= number_format($total['Revenue'] ?? 0, 2) ?></p>
    </div>

    <?php if (empty($sales)): ?>
        <p>No sales found for the selected period.</p>
    <?php else: ?>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Products</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?= date('M j, Y', strtotime($sale['Date'])) ?></td>
                        <td>#<?= $sale['Order_ID'] ?></td>
                        <td><?= htmlspecialchars($sale['Customer']) ?></td>
                        <td><?= htmlspecialchars($sale['Products']) ?></td>
                        <td>$<?= number_format($sale['Total'], 2) ?></td>
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