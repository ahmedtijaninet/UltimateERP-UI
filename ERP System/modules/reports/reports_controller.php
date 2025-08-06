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

    public function sales_report($action = null) {
        if ($action === 'export') {
            $this->export_sales_report();
            return;
        }

        $page_title = 'Sales Report by Customer';
        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : null;

        $report_data = $this->reports_model->getSalesReportData($start_date, $end_date);
        require_once 'modules/reports/sales_report_view.php';
    }

    private function export_sales_report() {
        $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : null;
        $data = $this->reports_model->getSalesReportData($start_date, $end_date);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="sales_report.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Customer Name', 'Total Orders', 'Total Sales']);

        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    public function inventory_report($action = null) {
        if ($action === 'export') {
            $this->export_inventory_report();
            return;
        }

        $page_title = 'Inventory Stock Levels Report';
        $category_id = isset($_GET['category_id']) && !empty($_GET['category_id']) ? $_GET['category_id'] : null;

        $report_data = $this->reports_model->getInventoryReportData($category_id);
        $categories = $this->reports_model->getCategories();
        require_once 'modules/reports/inventory_report_view.php';
    }

    private function export_inventory_report() {
        $category_id = isset($_GET['category_id']) && !empty($_GET['category_id']) ? $_GET['category_id'] : null;
        $data = $this->reports_model->getInventoryReportData($category_id);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="inventory_report.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Item Code', 'Description', 'Category', 'Qty on Hand', 'Unit Price', 'Stock Value']);

        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}
