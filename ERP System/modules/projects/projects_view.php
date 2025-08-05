<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Projects</h1>
        <a href="<?php echo SITE_URL; ?>/projects/add_project" class="btn" style="max-width: 200px;">Add New Project</a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Customer</th>
                <th>Manager</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($projects)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No projects found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['name']); ?></td>
                        <td><?php echo htmlspecialchars($project['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($project['manager_name']); ?></td>
                        <td><?php echo $project['start_date'] ? date('M j, Y', strtotime($project['start_date'])) : ''; ?></td>
                        <td><?php echo $project['end_date'] ? date('M j, Y', strtotime($project['end_date'])) : ''; ?></td>
                        <td><span class="status-<?php echo strtolower(str_replace(' ', '-', $project['status'])); ?>"><?php echo htmlspecialchars($project['status']); ?></span></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/projects/view_project/<?php echo $project['id']; ?>" class="action-link">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.status-not-started { color: #6c757d; font-weight: bold; }
.status-in-progress { color: #007bff; font-weight: bold; }
.status-completed { color: #28a745; font-weight: bold; }
.status-on-hold { color: #ffc107; font-weight: bold; }
.status-cancelled { color: #343a40; font-weight: bold; }
.action-link {
    text-decoration: none;
    color: #007bff;
    margin-right: 10px;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
