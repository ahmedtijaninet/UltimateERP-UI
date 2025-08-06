<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Category</h2>

    <form action="<?php echo SITE_URL; ?>/inventory/process_update_category" method="post">
        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="parent_category_id">Parent Category</label>
            <select name="parent_category_id" id="parent_category_id">
                <option value="">-- None --</option>
                <?php foreach ($categories as $cat): ?>
                    <?php if ($cat['id'] != $category['id']): // Prevent self-assignment ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($category['parent_category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Update Category</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/inventory/categories">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
