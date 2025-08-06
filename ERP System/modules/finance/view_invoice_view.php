<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div class="invoice-box">
        <div class="invoice-header-view">
            <div>
                <h1>INVOICE</h1>
                <p><strong>Invoice #:</strong> <?php echo htmlspecialchars($invoice['invoice_number']); ?></p>
                <p><strong>Status:</strong> <span class="status-<?php echo strtolower($invoice['status']); ?>"><?php echo htmlspecialchars($invoice['status']); ?></span></p>
            </div>
            <div>
                <p><strong>Issue Date:</strong> <?php echo date('M j, Y', strtotime($invoice['issue_date'])); ?></p>
                <p><strong>Due Date:</strong> <?php echo date('M j, Y', strtotime($invoice['due_date'])); ?></p>
            </div>
        </div>

        <hr>

        <div class="customer-info">
            <h3>Bill To:</h3>
            <p><strong><?php echo htmlspecialchars($invoice['customer_name']); ?></strong></p>
            <p><?php echo nl2br(htmlspecialchars($invoice['customer_address'])); ?></p>
            <p><?php echo htmlspecialchars($invoice['customer_email']); ?></p>
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
                <?php foreach ($invoice['items'] as $item): ?>
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
                    <td style="text-align: right;"><strong>Subtotal</strong></td>
                    <td>$<?php echo number_format($invoice['total_amount'], 2); ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: right;"><strong>Amount Paid</strong></td>
                    <td>$<?php echo number_format($invoice['paid_amount'], 2); ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: right; font-weight: bold; font-size: 1.2em;">Balance Due</td>
                    <td style="font-weight: bold; font-size: 1.2em;">$<?php echo number_format($invoice['total_amount'] - $invoice['paid_amount'], 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="invoice-actions">
            <a href="#" class="btn btn-secondary">Print Invoice</a>
            <a href="<?php echo SITE_URL; ?>/finance/invoices" class="btn btn-secondary">Back to Invoices</a>
        </div>
    </div>

    <div class="payment-section">
        <?php if ($auth_service->hasPermission('manage_finances')): ?>
        <div class="payment-form-container">
            <h3>Record a Payment</h3>
            <form action="<?php echo SITE_URL; ?>/finance/process_add_payment" method="post">
                <input type="hidden" name="invoice_id" value="<?php echo $invoice['id']; ?>">
                <div class="form-group">
                    <label for="payment_date">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" step="0.01" max="<?php echo $invoice['total_amount'] - $invoice['paid_amount']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select name="payment_method" id="payment_method">
                        <option>Bank Transfer</option>
                        <option>Credit Card</option>
                        <option>PayPal</option>
                        <option>Cash</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Record Payment</button>
            </form>
        </div>
        <?php endif; ?>
        <div class="payment-history-container">
            <h3>Payment History</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($payments)): ?>
                        <tr><td colspan="3" style="text-align: center;">No payments recorded yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo date('M j, Y', strtotime($payment['payment_date'])); ?></td>
                            <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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
.invoice-actions .btn {
    margin-left: 10px;
}
.status-draft { color: #6c757d; font-weight: bold; }
.status-sent { color: #007bff; font-weight: bold; }
.status-paid { color: #28a745; font-weight: bold; }
.status-overdue { color: #dc3545; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
.payment-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 40px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
}
.payment-form-container, .payment-history-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
@media (max-width: 768px) {
    .payment-section {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
