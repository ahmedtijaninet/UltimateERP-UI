<?php $page_title = 'Register'; ?>
<?php require_once '../../includes/views/header.php'; ?>

<div class="form-container">
    <h2>Create a New Account</h2>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITE_URL; ?>/register/process" method="post">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <button type="submit" class="btn">Register</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
        Already have an account? <a href="<?php echo SITE_URL; ?>/login">Login here</a>
    </p>
</div>

<?php require_once '../../includes/views/footer.php'; ?>
