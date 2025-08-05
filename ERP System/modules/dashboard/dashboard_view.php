<?php require_once '../../includes/views/header.php'; ?>

<div class="dashboard-container">
    <h1>Welcome to the Dashboard, <?php echo htmlspecialchars($username); ?>!</h1>
    <p>This is your central hub for managing all business operations.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Financial Overview</h3>
            <p>View key financial metrics, reports, and charts.</p>
            <a href="<?php echo SITE_URL; ?>/finance">Go to Finance</a>
        </div>
        <div class="widget">
            <h3>Sales & CRM</h3>
            <p>Manage customers, sales orders, and opportunities.</p>
            <a href="<?php echo SITE_URL; ?>/sales">Go to Sales</a>
        </div>
        <div class="widget">
            <h3>Inventory Control</h3>
            <p>Track stock levels, manage products, and handle procurement.</p>
            <a href="<?php echo SITE_URL; ?>/inventory">Go to Inventory</a>
        </div>
        <div class="widget">
            <h3>System Reports</h3>
            <p>Generate custom reports for all business areas.</p>
            <a href="<?php echo SITE_URL; ?>/reports">Go to Reports</a>
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
    border-left: 5px solid #007bff;
}
.widget h3 {
    margin-top: 0;
}
.widget a {
    text-decoration: none;
    font-weight: bold;
    color: #007bff;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
