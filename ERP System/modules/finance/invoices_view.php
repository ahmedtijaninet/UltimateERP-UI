<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Invoices</h1>
        <?php if ($auth_service->hasPermission('manage_finances')): ?>
            <a href="<?php echo SITE_URL; ?>/finance/create_invoice" class="btn" style="max-width: 200px;">Create New Invoice</a>
        <?php endif; ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($invoices)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No invoices found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($invoice['issue_date'])); ?></td>
                        <td><?php echo date('M j, Y', strtotime($invoice['due_date'])); ?></td>
                        <td>$<?php echo number_format($invoice['total_amount'], 2); ?></td>
                        <td><span class="status-<?php echo strtolower($invoice['status']); ?>"><?php echo htmlspecialchars($invoice['status']); ?></span></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/finance/view_invoice/<?php echo $invoice['id']; ?>" class="action-link">View</a>
                            <?php if ($auth_service->hasPermission('manage_finances') && $invoice['status'] != 'Paid' && $invoice['status'] != 'Cancelled'): ?>
                                <a href="<?php echo SITE_URL; ?>/finance/cancel_invoice/<?php echo $invoice['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to cancel this invoice?');">Cancel</a>
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
.status-sent { color: #007bff; font-weight: bold; }
.status-paid { color: #28a745; font-weight: bold; }
.status-overdue { color: #dc3545; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
.action-link {
    text-decoration: none;
    color: #007bff;
    margin-right: 10px;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
