<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Add a New Account</h2>

    <form action="<?php echo SITE_URL; ?>/finance/process_add_account" method="post">
        <div class="form-group">
            <label for="account_code">Account Code</label>
            <input type="text" name="account_code" id="account_code" required>
        </div>
        <div class="form-group">
            <label for="account_name">Account Name</label>
            <input type="text" name="account_name" id="account_name" required>
        </div>
        <div class="form-group">
            <label for="account_type">Account Type</label>
            <select name="account_type" id="account_type" required>
                <option value="">-- Select Type --</option>
                <option value="Asset">Asset</option>
                <option value="Liability">Liability</option>
                <option value="Equity">Equity</option>
                <option value="Revenue">Revenue</option>
                <option value="Expense">Expense</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"></textarea>
        </div>
        <button type="submit" class="btn">Add Account</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/finance/accounts">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
