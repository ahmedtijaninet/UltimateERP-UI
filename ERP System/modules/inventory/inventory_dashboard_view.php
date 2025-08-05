<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h1>Inventory Dashboard</h1>
    <p>Manage all aspects of your inventory, products, and suppliers.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Items</h3>
            <p>Manage your products and services.</p>
            <a href="<?php echo SITE_URL; ?>/inventory/items">View Items</a>
        </div>
        <div class="widget">
            <h3>Categories</h3>
            <p>Organize your items into categories.</p>
            <a href="<?php echo SITE_URL; ?>/inventory/categories">View Categories</a>
        </div>
        <div class="widget">
            <h3>Suppliers</h3>
            <p>Manage your vendors and suppliers.</p>
            <a href="<?php echo SITE_URL; ?>/inventory/suppliers">View Suppliers</a>
        </div>
        <div class="widget">
            <h3>Stock Control</h3>
            <p>View stock levels and manage adjustments.</p>
            <a href="<?php echo SITE_URL; ?>/inventory/stock">Manage Stock</a>
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
    border-left: 5px solid #28a745;
}
.widget h3 {
    margin-top: 0;
}
.widget a {
    text-decoration: none;
    font-weight: bold;
    color: #28a745;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
