<?php

// Start session
session_start();

// Load configuration and helpers
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'config/constants.php';
require_once 'includes/functions.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    $file = 'includes/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Simple Router
$request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$request_parts = explode('/', $request_uri);
$base_path = 'erp-system'; // This should match your base directory if running in a subdirectory

// Find the correct starting point of the route
$route_index = array_search($base_path, $request_parts);
if ($route_index !== false) {
    $route_parts = array_slice($request_parts, $route_index + 1);
} else {
    // This case handles if the app is at the root or path is clean
    if (!empty($request_parts) && $request_parts[0] == $base_path) {
       $route_parts = array_slice($request_parts, 1);
    } else {
       $route_parts = $request_parts;
    }
}
if (empty($route_parts) || (count($route_parts) == 1 && $route_parts[0] == "")) {
    $route_parts = [];
}

$page = !empty($route_parts[0]) ? $route_parts[0] : 'dashboard';
$action = !empty($route_parts[1]) ? $route_parts[1] : 'index';
$param = !empty($route_parts[2]) ? $route_parts[2] : null;

// Authentication check
$auth_service = new AuthService();
$is_logged_in = $auth_service->isLoggedIn();
$public_pages = ['login', 'register'];

if (!$is_logged_in && !in_array($page, $public_pages)) {
    redirect('login');
}

if ($is_logged_in && in_array($page, $public_pages)) {
    redirect('dashboard');
}


// Routing logic
$controller_name = '';
$controller_file = '';
$method_name = $page;

switch ($page) {
    case 'login':
    case 'register':
    case 'logout':
        $controller_name = 'AuthController';
        $controller_file = 'modules/auth/auth_controller.php';
        if ($action === 'process') {
            $method_name = $page . 'Process';
        }
        break;
    case 'dashboard':
        $controller_name = 'DashboardController';
        $controller_file = 'modules/dashboard/dashboard_controller.php';
        $method_name = 'index';
        break;
    case 'finance':
        $controller_name = 'FinanceController';
        $controller_file = 'modules/finance/finance_controller.php';
        $method_name = $action; // e.g., index, accounts, invoices
        break;
    case 'inventory':
        $controller_name = 'InventoryController';
        $controller_file = 'modules/inventory/inventory_controller.php';
        $method_name = $action; // e.g., index, items, categories
        break;
    default:
        // Handle 404
        header("HTTP/1.0 404 Not Found");
        include 'includes/views/header.php';
        echo "<div class='container'><h2>404 - Page Not Found</h2><p>The page you are looking for does not exist.</p></div>";
        include 'includes/views/footer.php';
        exit;
}


if (file_exists($controller_file)) {
    require_once $controller_file;
    if (class_exists($controller_name)) {
        $controller = new $controller_name();
        if (method_exists($controller, $method_name)) {
            // Call method with or without parameter
            if ($param !== null) {
                $controller->$method_name($param);
            } else {
                $controller->$method_name();
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 - Action not found: {$method_name}";
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "404 - Controller class not found: {$controller_name}";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 - Controller file not found: {$controller_file}";
}
