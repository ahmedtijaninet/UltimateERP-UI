<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Edit Employee</h2>

    <form action="<?php echo SITE_URL; ?>/hr/process_update_employee" method="post">
        <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="job_title">Job Title</label>
            <input type="text" name="job_title" id="job_title" value="<?php echo htmlspecialchars($employee['job_title']); ?>">
        </div>
        <div class="form-group">
            <label for="department_id">Department</label>
            <select name="department_id" id="department_id">
                <option value="">-- Select Department --</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?php echo $department['id']; ?>" <?php echo ($employee['department_id'] == $department['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($department['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="hire_date">Hire Date</label>
            <input type="date" name="hire_date" id="hire_date" value="<?php echo htmlspecialchars($employee['hire_date']); ?>">
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($employee['phone_number']); ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address" rows="3"><?php echo htmlspecialchars($employee['address']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo htmlspecialchars($employee['date_of_birth']); ?>">
        </div>
        <div class="form-group">
            <label for="salary">Salary</label>
            <input type="number" name="salary" id="salary" step="0.01" value="<?php echo htmlspecialchars($employee['salary']); ?>">
        </div>

        <button type="submit" class="btn">Update Employee</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        <a href="<?php echo SITE_URL; ?>/hr/employees">Cancel and go back</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
