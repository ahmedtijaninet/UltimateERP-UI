<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Suppliers</h1>
        <?php if ($auth_service->hasPermission('manage_inventory')): ?>
            <a href="<?php echo SITE_URL; ?>/inventory/add_supplier" class="btn" style="max-width: 200px;">Add New Supplier</a>
        <?php endif; ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($suppliers)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No suppliers found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['contact_person']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['email']); ?></td>
                        <td><?php echo htmlspecialchars($supplier['phone']); ?></td>
                        <td>
                            <?php if ($auth_service->hasPermission('manage_inventory')): ?>
                                <a href="<?php echo SITE_URL; ?>/inventory/edit_supplier/<?php echo $supplier['id']; ?>" class="action-link">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/inventory/delete_supplier/<?php echo $supplier['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
