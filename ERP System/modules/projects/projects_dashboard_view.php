<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h1>Project Management Dashboard</h1>
    <p>Manage all your company's projects and tasks.</p>

    <div class="dashboard-widgets">
        <div class="widget">
            <h3>Projects</h3>
            <p>View and manage all ongoing and completed projects.</p>
            <a href="<?php echo SITE_URL; ?>/projects/projects">View Projects</a>
        </div>
        <div class="widget">
            <h3>Tasks</h3>
            <p>View all tasks across all projects.</p>
            <a href="<?php echo SITE_URL; ?>/projects/tasks">View Tasks</a>
        </div>
        <div class="widget">
            <h3>Timesheets</h3>
            <p>Log and manage time spent on projects.</p>
            <a href="#">Coming Soon</a>
        </div>
        <div class="widget">
            <h3>Gantt Chart</h3>
            <p>Visualize project timelines and dependencies.</p>
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
    border-left: 5px solid #1f6f8b;
}
.widget h3 {
    margin-top: 0;
}
.widget a {
    text-decoration: none;
    font-weight: bold;
    color: #1f6f8b;
}
.widget a[href="#"] {
    color: #6c757d;
    cursor: not-allowed;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
