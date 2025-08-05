<?php

require_once 'modules/reports/models/ReportsModel.php';

class ReportsController {

    private $reports_model;

    public function __construct() {
        $this->reports_model = new ReportsModel();
    }

    /**
     * Main entry point for the reports module.
     * Displays the reports dashboard.
     */
    public function index() {
        $page_title = 'Reports Dashboard';
        require_once 'modules/reports/reports_dashboard_view.php';
    }

    // == REPORT METHODS ==

    public function sales_report() {
        $page_title = 'Sales Report by Customer';
        $report_data = $this->reports_model->getSalesReportData();
        require_once 'modules/reports/sales_report_view.php';
    }

    public function inventory_report() {
        $page_title = 'Inventory Stock Levels Report';
        $report_data = $this->reports_model->getInventoryReportData();
        require_once 'modules/reports/inventory_report_view.php';
    }
}
