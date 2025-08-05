<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h1>Finance Dashboard</h1>
    <p>Manage all financial aspects of your business from here.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Chart of Accounts</h3>
            <p>Manage your company's financial accounts.</p>
            <a href="<?php echo SITE_URL; ?>/finance/accounts">View Accounts</a>
        </div>
        <div class="widget">
            <h3>Invoices</h3>
            <p>Create and manage customer invoices.</p>
            <a href="<?php echo SITE_URL; ?>/finance/invoices">View Invoices</a>
        </div>
        <div class="widget">
            <h3>Payments</h3>
            <p>Track and record incoming payments.</p>
            <a href="<?php echo SITE_URL; ?>/finance/payments">View Payments</a>
        </div>
        <div class="widget">
            <h3>Reports</h3>
            <p>Generate financial reports and statements.</p>
            <a href="<?php echo SITE_URL; ?>/finance/reports">View Reports</a>
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
    border-left: 5px solid #17a2b8;
}
.widget h3 {
    margin-top: 0;
}
.widget a {
    text-decoration: none;
    font-weight: bold;
    color: #17a2b8;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
