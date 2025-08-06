<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div class="invoice-box">
        <div class="invoice-header-view">
            <div>
                <h1>PURCHASE ORDER</h1>
                <p><strong>PO #:</strong> <?php echo htmlspecialchars($po['po_number']); ?></p>
                <p><strong>Status:</strong> <span class="status-<?php echo strtolower(str_replace(' ', '-', $po['status'])); ?>"><?php echo htmlspecialchars($po['status']); ?></span></p>
            </div>
            <div>
                <p><strong>Order Date:</strong> <?php echo date('M j, Y', strtotime($po['order_date'])); ?></p>
                <p><strong>Expected Delivery:</strong> <?php echo $po['expected_delivery_date'] ? date('M j, Y', strtotime($po['expected_delivery_date'])) : 'N/A'; ?></p>
            </div>
        </div>

        <hr>

        <div class="customer-info">
            <h3>Supplier:</h3>
            <p><strong><?php echo htmlspecialchars($po['supplier_name']); ?></strong></p>
            <p><?php echo nl2br(htmlspecialchars($po['supplier_address'])); ?></p>
        </div>

        <table class="table invoice-items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($po['items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                        <td>$<?php echo number_format($item['total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: right; font-weight: bold; font-size: 1.2em;">Total Amount</td>
                    <td style="font-weight: bold; font-size: 1.2em;">$<?php echo number_format($po['total_amount'], 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="invoice-actions">
            <?php if ($auth_service->hasPermission('manage_purchasing')): ?>
                <?php if ($po['status'] == 'Draft'): ?>
                    <a href="<?php echo SITE_URL; ?>/purchasing/order_po/<?php echo $po['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to place this order?');">Place Order</a>
                <?php endif; ?>
                <?php if ($po['status'] == 'Ordered'): ?>
                    <a href="<?php echo SITE_URL; ?>/purchasing/receive_po/<?php echo $po['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to mark this order as received? This will add to inventory.');">Receive Goods</a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="<?php echo SITE_URL; ?>/purchasing/purchase_orders" class="btn btn-secondary">Back to Purchase Orders</a>
        </div>
    </div>
</div>

<style>
.invoice-box {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 20px auto;
}
.invoice-header-view {
    display: flex;
    justify-content: space-between;
}
.customer-info {
    margin: 20px 0;
}
.invoice-items-table tfoot td {
    border-top: 2px solid #333;
}
.invoice-actions {
    margin-top: 30px;
    text-align: right;
}
.status-draft { color: #6c757d; font-weight: bold; }
.status-ordered { color: #007bff; font-weight: bold; }
.status-partially-received { color: #fd7e14; font-weight: bold; }
.status-fully-received { color: #28a745; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
</style>

<?php require_once '../../includes/views/footer.php'; ?>
