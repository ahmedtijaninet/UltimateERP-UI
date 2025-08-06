<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Chart of Accounts</h1>
        <?php if ($auth_service->hasPermission('manage_finances')): ?>
            <a href="<?php echo SITE_URL; ?>/finance/add_account" class="btn" style="max-width: 200px;">Add New Account</a>
        <?php endif; ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Account Code</th>
                <th>Account Name</th>
                <th>Account Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($accounts)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No accounts found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($accounts as $account): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($account['account_code']); ?></td>
                        <td><?php echo htmlspecialchars($account['account_name']); ?></td>
                        <td><?php echo htmlspecialchars($account['account_type']); ?></td>
                        <td><?php echo htmlspecialchars($account['description']); ?></td>
                        <td>
                            <span class="status-<?php echo $account['is_active'] ? 'active' : 'inactive'; ?>">
                                <?php echo $account['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($auth_service->hasPermission('manage_finances')): ?>
                                <a href="<?php echo SITE_URL; ?>/finance/edit_account/<?php echo $account['id']; ?>" class="action-link">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/finance/delete_account/<?php echo $account['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.table th, .table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}
.table th {
    background-color: #f4f7f6;
    font-weight: bold;
}
.table tbody tr:nth-of-type(even) {
    background-color: #f9f9f9;
}
.status-active {
    color: #28a745;
    font-weight: bold;
}
.status-inactive {
    color: #dc3545;
    font-weight: bold;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
