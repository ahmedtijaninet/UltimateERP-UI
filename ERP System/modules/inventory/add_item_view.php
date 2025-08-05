<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Add a New Inventory Item</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <p><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITE_URL; ?>/inventory/process_add_item" method="post">
        <div class="form-group">
            <label for="item_code">Item Code</label>
            <input type="text" name="item_code" id="item_code" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" id="supplier_id">
                <option value="">-- Select Supplier --</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?php echo $supplier['id']; ?>"><?php echo htmlspecialchars($supplier['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="unit_price">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" step="0.01" value="0.00">
        </div>
        <div class="form-group">
            <label for="quantity_on_hand">Quantity on Hand</label>
            <input type="number" name="quantity_on_hand" id="quantity_on_hand" value="0">
        </div>
        <div class="form-group">
            <label for="reorder_level">Reorder Level</label>
            <input type="number" name="reorder_level" id="reorder_level" value="0">
        </div>

        <button type="submit" class="btn">Add Item</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/inventory/items">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
