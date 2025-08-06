<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h1>Human Resources Dashboard</h1>
    <p>Manage your company's employees, payroll, and performance.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Employees</h3>
            <p>Manage employee records and information.</p>
            <a href="<?php echo SITE_URL; ?>/hr/employees">View Employees</a>
        </div>
        <div class="widget">
            <h3>Payroll</h3>
            <p>Process payroll and view salary information.</p>
            <a href="#">Coming Soon</a>
        </div>
        <div class="widget">
            <h3>Attendance</h3>
            <p>Track employee attendance and leave.</p>
            <a href="#">Coming Soon</a>
        </div>
        <div class="widget">
            <h3>Performance</h3>
            <p>Manage employee performance reviews.</p>
            <a href="#">Coming Soon</a>
        </div>
    </div>
</div>

<style>
.dashboard-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}
.widget {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-left: 5px solid #fd7e14;
}
.widget h3 {
    margin-top: 0;
}
.widget a {
    text-decoration: none;
    font-weight: bold;
    color: #fd7e14;
}
.widget a[href="#"] {
    color: #6c757d;
    cursor: not-allowed;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
