<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Customer</h2>

    <form action="<?php echo SITE_URL; ?>/sales/process_update_customer" method="post">
        <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
        <div class="form-group">
            <label for="name">Customer Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="contact_person">Contact Person</label>
            <input type="text" name="contact_person" id="contact_person" value="<?php echo htmlspecialchars($customer['contact_person']); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($customer['email']); ?>">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" rows="3"><?php echo htmlspecialchars($customer['address']); ?></textarea>
        </div>

        <button type="submit" class="btn">Update Customer</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/sales/customers">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
