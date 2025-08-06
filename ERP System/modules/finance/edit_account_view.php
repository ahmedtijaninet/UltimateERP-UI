<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Account</h2>

    <form action="<?php echo SITE_URL; ?>/finance/process_update_account" method="post">
        <input type="hidden" name="id" value="<?php echo $account['id']; ?>">
        <div class="form-group">
            <label for="account_code">Account Code</label>
            <input type="text" name="account_code" id="account_code" value="<?php echo htmlspecialchars($account['account_code']); ?>" required>
        </div>
        <div class="form-group">
            <label for="account_name">Account Name</label>
            <input type="text" name="account_name" id="account_name" value="<?php echo htmlspecialchars($account['account_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="account_type">Account Type</label>
            <select name="account_type" id="account_type" required>
                <option value="">-- Select Type --</option>
                <option value="Asset" <?php echo ($account['account_type'] == 'Asset') ? 'selected' : ''; ?>>Asset</option>
                <option value="Liability" <?php echo ($account['account_type'] == 'Liability') ? 'selected' : ''; ?>>Liability</option>
                <option value="Equity" <?php echo ($account['account_type'] == 'Equity') ? 'selected' : ''; ?>>Equity</option>
                <option value="Revenue" <?php echo ($account['account_type'] == 'Revenue') ? 'selected' : ''; ?>>Revenue</option>
                <option value="Expense" <?php echo ($account['account_type'] == 'Expense') ? 'selected' : ''; ?>>Expense</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($account['description']); ?></textarea>
        </div>
        <button type="submit" class="btn">Update Account</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/finance/accounts">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
