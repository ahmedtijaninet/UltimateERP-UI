<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Inventory Item</h2>

    <form action="<?php echo SITE_URL; ?>/inventory/process_update_item" method="post">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
        <div class="form-group">
            <label for="item_code">Item Code</label>
            <input type="text" name="item_code" id="item_code" value="<?php echo htmlspecialchars($item['item_code']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" required><?php echo htmlspecialchars($item['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($item['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" id="supplier_id">
                <option value="">-- Select Supplier --</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?php echo $supplier['id']; ?>" <?php echo ($item['supplier_id'] == $supplier['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($supplier['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" step="0.01" value="<?php echo $item['unit_price']; ?>">
        </div>
        <div class="form-group">
            <label for="quantity_on_hand">Quantity on Hand</label>
            <input type="number" name="quantity_on_hand" id="quantity_on_hand" value="<?php echo $item['quantity_on_hand']; ?>" readonly>
            <small>Quantity is adjusted via Stock Adjustments, not here.</small>
        </div>
        <div class="form-group">
            <label for="reorder_level">Reorder Level</label>
            <input type="number" name="reorder_level" id="reorder_level" value="<?php echo $item['reorder_level']; ?>">
        </div>

        <button type="submit" class="btn">Update Item</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/inventory/items">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
