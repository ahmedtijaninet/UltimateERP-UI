<?php

class DashboardController {

    public function __construct() {
        // In a real app, you'd load models here
    }

    public function index() {
        // This is a protected route, so we know the user is logged in.
        $username = $_SESSION['username'];
        $page_title = 'Dashboard';

        // Load the dashboard view
        require_once 'modules/dashboard/dashboard_view.php';
    }
}
