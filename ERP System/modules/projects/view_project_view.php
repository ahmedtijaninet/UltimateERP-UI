<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div class="project-header">
        <h1><?php echo htmlspecialchars($project['name']); ?></h1>
        <p><strong>Status:</strong> <span class="status-<?php echo strtolower(str_replace(' ', '-', $project['status'])); ?>"><?php echo htmlspecialchars($project['status']); ?></span></p>
        <p><strong>Manager:</strong> <?php echo htmlspecialchars($project['manager_name']); ?></p>
        <p><strong>Customer:</strong> <?php echo htmlspecialchars($project['customer_name']); ?></p>
    </div>

    <div class="project-details">
        <div>
            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
        </div>
        <div>
            <h3>Details</h3>
            <ul>
                <li><strong>Start Date:</strong> <?php echo $project['start_date'] ? date('M j, Y', strtotime($project['start_date'])) : 'N/A'; ?></li>
                <li><strong>End Date:</strong> <?php echo $project['end_date'] ? date('M j, Y', strtotime($project['end_date'])) : 'N/A'; ?></li>
                <li><strong>Budget:</strong> $<?php echo number_format($project['budget'], 2); ?></li>
            </ul>
        </div>
    </div>

    <hr>

    <div class="task-section">
        <h2>Project Tasks</h2>
        <div class="task-container">
            <div class="task-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Assignee</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tasks)): ?>
                            <tr><td colspan="4" style="text-align: center;">No tasks found for this project.</td></tr>
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                <td><?php echo htmlspecialchars($task['assignee_name']); ?></td>
                                <td><?php echo $task['due_date'] ? date('M j, Y', strtotime($task['due_date'])) : ''; ?></td>
                                <td><span class="status-<?php echo strtolower(str_replace(' ', '-', $task['status'])); ?>"><?php echo htmlspecialchars($task['status']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="add-task-form">
                <h3>Add New Task</h3>
                <form action="<?php echo SITE_URL; ?>/projects/process_add_task" method="post">
                    <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="assignee_id">Assign To</label>
                        <select name="assignee_id" id="assignee_id">
                            <option value="">-- Unassigned --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" name="due_date" id="due_date">
                    </div>
                    <button type="submit" class="btn">Add Task</button>
                </form>
            </div>
        </div>
    </div>

</div>

<style>
.project-header {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.project-header h1 {
    margin: 0;
}
.project-details {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
.task-section {
    margin-top: 20px;
}
.status-not-started { color: #6c757d; font-weight: bold; }
.status-in-progress { color: #007bff; font-weight: bold; }
.status-completed { color: #28a745; font-weight: bold; }
.status-on-hold { color: #ffc107; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
.task-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}
.add-task-form {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
}
.status-to-do { color: #6c757d; }
.status-in-progress { color: #007bff; }
.status-done { color: #28a745; }
.status-blocked { color: #dc3545; }
</style>

<?php require_once '../../includes/views/footer.php'; ?>
