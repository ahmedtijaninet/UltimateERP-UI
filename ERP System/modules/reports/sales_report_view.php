<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Sales Report by Customer</h1>
        <a href="<?php echo SITE_URL; ?>/reports" class="btn btn-secondary" style="max-width: 200px;">Back to Reports</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Total Orders</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($report_data)): ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No sales data found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($report_data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo $row['total_orders']; ?></td>
                        <td><strong>$<?php echo number_format($row['total_sales'], 2); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
