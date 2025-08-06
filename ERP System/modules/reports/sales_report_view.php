<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Sales Report by Customer</h1>
        <div>
            <a href="<?php echo SITE_URL; ?>/reports/sales_report/export" class="btn">Export to CSV</a>
            <a href="<?php echo SITE_URL; ?>/reports" class="btn btn-secondary" style="max-width: 200px;">Back to Reports</a>
        </div>
    </div>

    <div class="report-filters">
        <form action="<?php echo SITE_URL; ?>/reports/sales_report" method="get">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
            </div>
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>

    <div class="chart-container">
        <canvas id="salesChart"></canvas>
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
                    <td colspan="3" style="text-align: center;">No sales data found for the selected period.</td>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const reportData = <?php echo json_encode($report_data); ?>;

    if (reportData.length > 0) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: reportData.map(row => row.customer_name),
                datasets: [{
                    label: 'Total Sales',
                    data: reportData.map(row => row.total_sales),
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
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
