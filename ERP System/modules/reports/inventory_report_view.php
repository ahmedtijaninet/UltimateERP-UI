<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Inventory Stock Levels Report</h1>
        <a href="<?php echo SITE_URL; ?>/reports" class="btn btn-secondary" style="max-width: 200px;">Back to Reports</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Description</th>
                <th>Category</th>
                <th>Qty on Hand</th>
                <th>Unit Price</th>
                <th>Stock Value</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($report_data)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No inventory data found.</td>
                </tr>
            <?php else: ?>
                <?php
                $total_stock_value = 0;
                foreach ($report_data as $row):
                    $total_stock_value += $row['stock_value'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['item_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo $row['quantity_on_hand']; ?></td>
                        <td>$<?php echo number_format($row['unit_price'], 2); ?></td>
                        <td><strong>$<?php echo number_format($row['stock_value'], 2); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold; font-size: 1.2em;">Total Stock Value</td>
                <td style="font-weight: bold; font-size: 1.2em;">$<?php echo number_format($total_stock_value, 2); ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
