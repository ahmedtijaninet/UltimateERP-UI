<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Supplier</h2>

    <form action="<?php echo SITE_URL; ?>/inventory/process_update_supplier" method="post">
        <input type="hidden" name="id" value="<?php echo $supplier['id']; ?>">
        <div class="form-group">
            <label for="name">Supplier Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($supplier['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="contact_person">Contact Person</label>
            <input type="text" name="contact_person" id="contact_person" value="<?php echo htmlspecialchars($supplier['contact_person']); ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($supplier['email']); ?>">
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($supplier['phone']); ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" rows="3"><?php echo htmlspecialchars($supplier['address']); ?></textarea>
        </div>

        <button type="submit" class="btn">Update Supplier</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/inventory/suppliers">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
