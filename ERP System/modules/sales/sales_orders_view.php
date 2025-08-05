<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Sales Orders</h1>
        <a href="<?php echo SITE_URL; ?>/sales/create_so" class="btn" style="max-width: 250px;">Create New Sales Order</a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>SO #</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($sales_orders)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No sales orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($sales_orders as $so): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($so['so_number']); ?></td>
                        <td><?php echo htmlspecialchars($so['customer_name']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($so['order_date'])); ?></td>
                        <td>$<?php echo number_format($so['total_amount'], 2); ?></td>
                        <td><span class="status-<?php echo strtolower(str_replace(' ', '-', $so['status'])); ?>"><?php echo htmlspecialchars($so['status']); ?></span></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/sales/view_so/<?php echo $so['id']; ?>" class="action-link">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.status-draft { color: #6c757d; font-weight: bold; }
.status-confirmed { color: #007bff; font-weight: bold; }
.status-invoiced { color: #17a2b8; font-weight: bold; }
.status-shipped { color: #28a745; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
.action-link {
    text-decoration: none;
    color: #007bff;
    margin-right: 10px;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
