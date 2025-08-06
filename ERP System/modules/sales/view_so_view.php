<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div class="invoice-box">
        <div class="invoice-header-view">
            <div>
                <h1>SALES ORDER</h1>
                <p><strong>SO #:</strong> <?php echo htmlspecialchars($so['so_number']); ?></p>
                <p><strong>Status:</strong> <span class="status-<?php echo strtolower(str_replace(' ', '-', $so['status'])); ?>"><?php echo htmlspecialchars($so['status']); ?></span></p>
            </div>
            <div>
                <p><strong>Order Date:</strong> <?php echo date('M j, Y', strtotime($so['order_date'])); ?></p>
            </div>
        </div>

        <hr>

        <div class="customer-info">
            <h3>Customer:</h3>
            <p><strong><?php echo htmlspecialchars($so['customer_name']); ?></strong></p>
            <p><?php echo nl2br(htmlspecialchars($so['customer_address'])); ?></p>
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
                <?php foreach ($so['items'] as $item): ?>
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
                    <td style="font-weight: bold; font-size: 1.2em;">$<?php echo number_format($so['total_amount'], 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="invoice-actions">
            <?php if ($auth_service->hasPermission('manage_sales')): ?>
                <?php if ($so['status'] == 'Draft'): ?>
                    <a href="<?php echo SITE_URL; ?>/sales/confirm_so/<?php echo $so['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to confirm this sales order?');">Confirm Order</a>
                <?php endif; ?>
                <?php if ($so['status'] == 'Confirmed'): ?>
                    <a href="<?php echo SITE_URL; ?>/sales/ship_order/<?php echo $so['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to mark this order as shipped? This will deduct from inventory.');">Ship Order</a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="<?php echo SITE_URL; ?>/sales/sales_orders" class="btn btn-secondary">Back to Sales Orders</a>
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
.status-confirmed { color: #007bff; font-weight: bold; }
.status-invoiced { color: #17a2b8; font-weight: bold; }
.status-shipped { color: #28a745; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
</style>

<?php require_once '../../includes/views/footer.php'; ?>
