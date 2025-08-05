<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">
            <a href="<?php echo SITE_URL; ?>"><?php echo SITE_NAME; ?></a>
        </div>
        <ul class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo SITE_URL; ?>/dashboard">Dashboard</a></li>
                <li><a href="<?php echo SITE_URL; ?>/finance">Finance</a></li>
                <li><a href="<?php echo SITE_URL; ?>/sales">Sales</a></li>
                <li><a href="<?php echo SITE_URL; ?>/inventory">Inventory</a></li>
                <li><a href="<?php echo SITE_URL; ?>/logout">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo SITE_URL; ?>/login">Login</a></li>
                <li><a href="<?php echo SITE_URL; ?>/register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main class="container">
