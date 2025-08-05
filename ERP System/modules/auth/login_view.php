<?php $page_title = 'Login'; ?>
<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Login to Your Account</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <p><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITE_URL; ?>/login/process" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <button type="submit" class="btn">Login</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        Don't have an account? <a href="<?php echo SITE_URL; ?>/register">Register here</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
