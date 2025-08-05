<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Inventory Items</h1>
        <a href="<?php echo SITE_URL; ?>/inventory/add_item" class="btn" style="max-width: 200px;">Add New Item</a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Description</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Unit Price</th>
                <th>Qty on Hand</th>
                <th>Reorder Level</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No items found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_code']); ?></td>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['supplier_name']); ?></td>
                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                        <td><?php echo $item['quantity_on_hand']; ?></td>
                        <td><?php echo $item['reorder_level']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
