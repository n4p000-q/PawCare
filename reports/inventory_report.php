<?php
require_once '../config/db.php';

// Fetch inventory data
$stmt = $pdo->query("
    SELECT p.Product_ID, p.Name, p.Category, p.Price, i.Stock_Level, i.Supplier
    FROM Products p
    JOIN Inventory i ON p.Product_ID = i.Product_ID
    ORDER BY p.Category, p.Name
");
$inventory = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory Report</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
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
</body>
</html>