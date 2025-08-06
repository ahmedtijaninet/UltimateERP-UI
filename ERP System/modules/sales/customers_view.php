<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Customers</h1>
        <a href="<?php echo SITE_URL; ?>/sales/add_customer" class="btn" style="max-width: 200px;">Add New Customer</a>
    </div>

    <div class="search-bar">
        <form action="<?php echo SITE_URL; ?>/sales/customers" method="get">
            <input type="text" name="search" placeholder="Search by Name or Email..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn">Search</button>
        </form>
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
            <?php if (empty($customers)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No customers found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['contact_person']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/sales/edit_customer/<?php echo $customer['id']; ?>" class="action-link">Edit</a>
                            <a href="<?php echo SITE_URL; ?>/sales/delete_customer/<?php echo $customer['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
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
