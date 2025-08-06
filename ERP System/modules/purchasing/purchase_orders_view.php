<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Purchase Orders</h1>
        <?php if ($auth_service->hasPermission('manage_purchasing')): ?>
            <a href="<?php echo SITE_URL; ?>/purchasing/create_po" class="btn" style="max-width: 250px;">Create New Purchase Order</a>
        <?php endif; ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>PO #</th>
                <th>Supplier</th>
                <th>Order Date</th>
                <th>Expected Delivery</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($purchase_orders)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No purchase orders found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($purchase_orders as $po): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($po['po_number']); ?></td>
                        <td><?php echo htmlspecialchars($po['supplier_name']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($po['order_date'])); ?></td>
                        <td><?php echo $po['expected_delivery_date'] ? date('M j, Y', strtotime($po['expected_delivery_date'])) : 'N/A'; ?></td>
                        <td>$<?php echo number_format($po['total_amount'], 2); ?></td>
                        <td><span class="status-<?php echo strtolower(str_replace(' ', '-', $po['status'])); ?>"><?php echo htmlspecialchars($po['status']); ?></span></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/purchasing/view_po/<?php echo $po['id']; ?>" class="action-link">View</a>
                            <?php if ($auth_service->hasPermission('manage_purchasing')): ?>
                                <?php if ($po['status'] == 'Draft'): ?>
                                    <a href="<?php echo SITE_URL; ?>/purchasing/order_po/<?php echo $po['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to place this order?');">Order</a>
                                <?php endif; ?>
                                <?php if ($po['status'] == 'Ordered'): ?>
                                    <a href="<?php echo SITE_URL; ?>/purchasing/receive_po/<?php echo $po['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to mark this order as received? This will add to inventory.');">Receive</a>
                                <?php endif; ?>
                                <?php if ($po['status'] != 'Fully Received' && $po['status'] != 'Cancelled'): ?>
                                    <a href="<?php echo SITE_URL; ?>/purchasing/cancel_po/<?php echo $po['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to cancel this purchase order?');">Cancel</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.status-draft { color: #6c757d; font-weight: bold; }
.status-ordered { color: #007bff; font-weight: bold; }
.status-partially-received { color: #fd7e14; font-weight: bold; }
.status-fully-received { color: #28a745; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
.action-link {
    text-decoration: none;
    color: #007bff;
    margin-right: 10px;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
