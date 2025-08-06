<?php
require_once '../../includes/views/header.php';
$auth_service = new AuthService();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Employees</h1>
        <?php if ($auth_service->hasPermission('manage_hr')): ?>
            <a href="<?php echo SITE_URL; ?>/hr/add_employee" class="btn" style="max-width: 200px;">Add New Employee</a>
        <?php endif; ?>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Job Title</th>
                <th>Department</th>
                <th>Hire Date</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No employees found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($employee['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                        <td><?php echo $employee['hire_date'] ? date('M j, Y', strtotime($employee['hire_date'])) : ''; ?></td>
                        <td><?php echo htmlspecialchars($employee['username']); ?></td>
                        <td>
                            <?php if ($auth_service->hasPermission('manage_hr')): ?>
                                <a href="<?php echo SITE_URL; ?>/hr/edit_employee/<?php echo $employee['id']; ?>" class="action-link">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/hr/delete_employee/<?php echo $employee['id']; ?>" class="action-link" onclick="return confirm('Are you sure you want to delete this employee record?');">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
