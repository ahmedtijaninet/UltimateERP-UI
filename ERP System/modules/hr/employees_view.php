<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Employees</h1>
        <a href="<?php echo SITE_URL; ?>/hr/add_employee" class="btn" style="max-width: 200px;">Add New Employee</a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Job Title</th>
                <th>Department</th>
                <th>Hire Date</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No employees found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($employee['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                        <td><?php echo $employee['hire_date'] ? date('M j, Y', strtotime($employee['hire_date'])) : ''; ?></td>
                        <td><?php echo htmlspecialchars($employee['username']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
