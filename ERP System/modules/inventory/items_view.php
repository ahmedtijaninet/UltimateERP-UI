<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Inventory Items</h1>
        <?php if ($auth_service->hasPermission('manage_inventory')): ?>
            <a href="<?php echo SITE_URL; ?>/inventory/add_item" class="btn" style="max-width: 200px;">Add New Item</a>
        <?php endif; ?>
    </div>

    <div class="search-bar">
        <form action="<?php echo SITE_URL; ?>/inventory/items" method="get">
            <input type="text" name="search" placeholder="Search by Item Code or Description..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn">Search</button>
        </form>
    </div>

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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="8" style="text-align: center;">No items found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_code']); ?></td>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['supplier_name']); ?></td>
                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                        <td><strong><?php echo $item['quantity_on_hand']; ?></strong></td>
                        <td><?php echo $item['reorder_level']; ?></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/inventory/view_item/<?php echo $item['id']; ?>" class="action-link">View</a>
                            <?php if ($auth_service->hasPermission('manage_inventory')): ?>
                                <a href="<?php echo SITE_URL; ?>/inventory/edit_item/<?php echo $item['id']; ?>" class="action-link">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/inventory/delete_item/<?php echo $item['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.search-bar {
    margin: 20px 0;
}
.search-bar form {
    display: flex;
    gap: 10px;
}
.search-bar input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
