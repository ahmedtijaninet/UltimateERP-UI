<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Item Categories</h1>
        <a href="<?php echo SITE_URL; ?>/inventory/add_category" class="btn" style="max-width: 200px;">Add New Category</a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Parent Category</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="3" style="text-align: center;">No categories found.</td>
                </tr>
            <?php else: ?>
                <?php
                // Create a lookup for parent category names
                $category_lookup = [];
                foreach ($categories as $category) {
                    $category_lookup[$category['id']] = $category['name'];
                }
                ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                        <td>
                            <?php
                            if (!empty($category['parent_category_id'])) {
                                echo htmlspecialchars($category_lookup[$category['parent_category_id']] ?? 'N/A');
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
