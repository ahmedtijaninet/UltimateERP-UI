<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h1>Sales & CRM Dashboard</h1>
    <p>Manage your customers, sales orders, and sales pipeline.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Customers</h3>
            <p>Manage your customer database.</p>
            <a href="<?php echo SITE_URL; ?>/sales/customers">View Customers</a>
        </div>
        <div class="widget">
            <h3>Sales Orders</h3>
            <p>Create and manage sales orders.</p>
            <a href="<?php echo SITE_URL; ?>/sales/sales_orders">View Sales Orders</a>
        </div>
        <div class="widget">
            <h3>Quotes</h3>
            <p>Create and manage sales quotations.</p>
            <a href="<?php echo SITE_URL; ?>/sales/quotes">View Quotes</a>
        </div>
        <div class="widget">
            <h3>Opportunities</h3>
            <p>Track potential sales deals.</p>
            <a href="<?php echo SITE_URL; ?>/sales/opportunities">View Opportunities</a>
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
    border-left: 5px solid #6610f2;
}
.widget h3 {
    margin-top: 0;
}
.widget a {
    text-decoration: none;
    font-weight: bold;
    color: #6610f2;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
