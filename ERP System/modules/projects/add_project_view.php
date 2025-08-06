<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Add a New Project</h2>

    <form action="<?php echo SITE_URL; ?>/projects/process_add_project" method="post">
        <div class="form-group">
            <label for="name">Project Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id">
                <option value="">-- Select Customer --</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo $customer['id']; ?>"><?php echo htmlspecialchars($customer['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="manager_id">Project Manager</label>
            <select name="manager_id" id="manager_id">
                <option value="">-- Select Manager --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date">
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date">
        </div>
        <div class="form-group">
            <label for="budget">Budget</label>
            <input type="number" name="budget" id="budget" step="0.01">
        </div>

        <button type="submit" class="btn">Add Project</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/projects/projects">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
