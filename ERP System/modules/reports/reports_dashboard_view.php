<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h1>Reports Dashboard</h1>
    <p>Generate and view reports for all business areas.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Sales Reports</h3>
            <p>Analyze sales performance and customer data.</p>
            <a href="<?php echo SITE_URL; ?>/reports/sales_report">Sales by Customer</a>
        </div>
        <div class="widget">
            <h3>Inventory Reports</h3>
            <p>View stock levels and inventory valuation.</p>
            <a href="<?php echo SITE_URL; ?>/reports/inventory_report">Inventory Stock Levels</a>
        </div>
        <div class="widget">
            <h3>Financial Reports</h3>
            <p>Generate financial statements like P&L and Balance Sheets.</p>
            <a href="#">Coming Soon</a>
        </div>
        <div class="widget">
            <h3>Purchasing Reports</h3>
            <p>Analyze procurement and supplier performance.</p>
            <a href="#">Coming Soon</a>
        </div>
    </div>
</div>

<style>
.dashboard-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}
.widget {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-left: 5px solid #dc3545;
}
.widget h3 {
    margin-top: 0;
}
.widget a {
    text-decoration: none;
    font-weight: bold;
    color: #dc3545;
}
.widget a[href="#"] {
    color: #6c757d;
    cursor: not-allowed;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
