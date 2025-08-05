<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Add a New Category</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <p><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITE_URL; ?>/inventory/process_add_category" method="post">
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="parent_category_id">Parent Category</label>
            <select name="parent_category_id" id="parent_category_id">
                <option value="">-- None --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Add Category</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/inventory/categories">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
