<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Task</h2>

    <form action="<?php echo SITE_URL; ?>/projects/process_update_task" method="post">
        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
        <input type="hidden" name="project_id" value="<?php echo $task['project_id']; ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="3"><?php echo htmlspecialchars($task['description']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="assignee_id">Assign To</label>
            <select name="assignee_id" id="assignee_id">
                <option value="">-- Unassigned --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo ($task['assignee_id'] == $user['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date</label>
            <input type="date" name="due_date" id="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>">
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="To Do" <?php echo ($task['status'] == 'To Do') ? 'selected' : ''; ?>>To Do</option>
                <option value="In Progress" <?php echo ($task['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                <option value="Done" <?php echo ($task['status'] == 'Done') ? 'selected' : ''; ?>>Done</option>
                <option value="Blocked" <?php echo ($task['status'] == 'Blocked') ? 'selected' : ''; ?>>Blocked</option>
            </select>
        </div>

        <button type="submit" class="btn">Update Task</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/projects/view_project/<?php echo $task['project_id']; ?>">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
