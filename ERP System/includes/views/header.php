<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <style>
        /* Toast Notifications */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast {
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 4px;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            opacity: 0;
            transition: opacity 0.3s, transform 0.3s;
            transform: translateX(100%);
        }
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        .toast.success { background-color: #28a745; }
        .toast.error { background-color: #dc3545; }
    </style>
</head>
<body
    <?php
    if (isset($_SESSION['toast_message'])) {
        echo 'data-toast-message="' . htmlspecialchars($_SESSION['toast_message']['message']) . '" ';
        echo 'data-toast-type="' . htmlspecialchars($_SESSION['toast_message']['type']) . '"';
        unset($_SESSION['toast_message']);
    }
    ?>
>

<div id="toast-container"></div>

<header>
    <nav>
        <div class="logo">
            <a href="<?php echo SITE_URL; ?>"><?php echo SITE_NAME; ?></a>
        </div>
        <ul class="nav-links">
            <?php if (isset($_SESSION['user_id'])):
                $auth_service = new AuthService();
            ?>
                <li><a href="<?php echo SITE_URL; ?>/dashboard">Dashboard</a></li>
                <?php if ($auth_service->hasPermission('manage_finances')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/finance">Finance</a></li>
                <?php endif; ?>
                <?php if ($auth_service->hasPermission('manage_sales')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/sales">Sales</a></li>
                <?php endif; ?>
                <?php if ($auth_service->hasPermission('manage_inventory')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/inventory">Inventory</a></li>
                <?php endif; ?>
                 <?php if ($auth_service->hasPermission('manage_purchasing')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/purchasing">Purchasing</a></li>
                <?php endif; ?>
                 <?php if ($auth_service->hasPermission('manage_hr')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/hr">HR</a></li>
                <?php endif; ?>
                 <?php if ($auth_service->hasPermission('manage_projects')): ?>
                    <li><a href="<?php echo SITE_URL; ?>/projects">Projects</a></li>
                <?php endif; ?>
                <li><a href="<?php echo SITE_URL; ?>/reports">Reports</a></li>
                <li><a href="<?php echo SITE_URL; ?>/logout">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo SITE_URL; ?>/login">Login</a></li>
                <li><a href="<?php echo SITE_URL; ?>/register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main class="container">
