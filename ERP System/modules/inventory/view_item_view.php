<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div class="item-header">
        <h1><?php echo htmlspecialchars($item['item_code']); ?></h1>
        <p><?php echo htmlspecialchars($item['description']); ?></p>
        <h2>Current Stock: <?php echo $item['quantity_on_hand']; ?></h2>
    </div>

    <div class="stock-management-section">
        <div class="stock-adjustment-container">
            <h3>Adjust Stock</h3>
            <form action="<?php echo SITE_URL; ?>/inventory/process_stock_adjustment" method="post">
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                <div class="form-group">
                    <label for="quantity_change">Quantity Change</label>
                    <input type="number" name="quantity_change" id="quantity_change" placeholder="e.g., -5 or 10" required>
                    <small>Use negative numbers to decrease stock.</small>
                </div>
                <div class="form-group">
                    <label for="notes">Reason / Notes</label>
                    <textarea name="notes" id="notes" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn">Adjust Stock</button>
            </form>
        </div>
        <div class="transaction-history-container">
            <h3>Stock Movement History</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Change</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="4" style="text-align: center;">No transactions found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td><?php echo date('M j, Y H:i', strtotime($tx['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($tx['transaction_type']); ?></td>
                            <td><strong><?php echo $tx['quantity_change']; ?></strong></td>
                            <td><?php echo htmlspecialchars($tx['notes']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.item-header {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.item-header h1 {
    margin: 0;
}
.stock-management-section {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 20px;
}
.stock-adjustment-container, .transaction-history-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
@media (max-width: 768px) {
    .stock-management-section {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
