<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Inventory Stock Levels Report</h1>
        <div>
            <a href="<?php echo SITE_URL; ?>/reports/inventory_report/export" class="btn">Export to CSV</a>
            <a href="<?php echo SITE_URL; ?>/reports" class="btn btn-secondary" style="max-width: 200px;">Back to Reports</a>
        </div>
    </div>

    <div class="report-filters">
        <form action="<?php echo SITE_URL; ?>/reports/inventory_report" method="get">
            <div class="form-group">
                <label for="category_id">Filter by Category</label>
                <select name="category_id" id="category_id">
                    <option value="">-- All Categories --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>

    <div class="chart-container">
        <canvas id="inventoryChart"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const reportData = <?php echo json_encode($report_data); ?>;

    if (reportData.length > 0) {
        // For inventory, let's chart the top 10 items by value
        const topItems = reportData.slice(0, 10);

        const ctx = document.getElementById('inventoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: topItems.map(row => row.item_code),
                datasets: [{
                    label: 'Stock Value',
                    data: topItems.map(row => row.stock_value),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(201, 203, 207, 0.7)',
                        'rgba(23, 162, 184, 0.7)',
                        'rgba(108, 117, 125, 0.7)',
                        'rgba(244, 67, 54, 0.7)'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Top 10 Items by Stock Value'
                    }
                }
            }
        });
    }
});
</script>

<style>
.report-filters {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}
.report-filters form {
    display: flex;
    gap: 20px;
    align-items: flex-end;
}
.chart-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
