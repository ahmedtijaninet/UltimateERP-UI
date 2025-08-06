<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Project</h2>

    <form action="<?php echo SITE_URL; ?>/projects/process_update_project" method="post">
        <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
        <div class="form-group">
            <label for="name">Project Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($project['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($project['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id">
                <option value="">-- Select Customer --</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo $customer['id']; ?>" <?php echo ($project['customer_id'] == $customer['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($customer['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="manager_id">Project Manager</label>
            <select name="manager_id" id="manager_id">
                <option value="">-- Select Manager --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo ($project['manager_id'] == $user['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($project['start_date']); ?>">
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($project['end_date']); ?>">
        </div>
        <div class="form-group">
            <label for="budget">Budget</label>
            <input type="number" name="budget" id="budget" step="0.01" value="<?php echo htmlspecialchars($project['budget']); ?>">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="Not Started" <?php echo ($project['status'] == 'Not Started') ? 'selected' : ''; ?>>Not Started</option>
                <option value="In Progress" <?php echo ($project['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                <option value="Completed" <?php echo ($project['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                <option value="On Hold" <?php echo ($project['status'] == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                <option value="Cancelled" <?php echo ($project['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn">Update Project</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/projects/projects">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
