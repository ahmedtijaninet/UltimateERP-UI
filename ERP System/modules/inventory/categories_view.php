<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Item Categories</h1>
        <?php if ($auth_service->hasPermission('manage_inventory')): ?>
            <a href="<?php echo SITE_URL; ?>/inventory/add_category" class="btn" style="max-width: 200px;">Add New Category</a>
        <?php endif; ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Parent Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No categories found.</td>
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
                        <td>
                            <?php if ($auth_service->hasPermission('manage_inventory')): ?>
                                <a href="<?php echo SITE_URL; ?>/inventory/edit_category/<?php echo $category['id']; ?>" class="action-link">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/inventory/delete_category/<?php echo $category['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
