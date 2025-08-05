<?php

require_once 'includes/BaseModel.php';

class ReportsModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // == REPORTING METHODS (to be implemented) ==

    public function getSalesReportData() {
        $this->db->query("
            SELECT
                c.name as customer_name,
                COUNT(so.id) as total_orders,
                SUM(so.total_amount) as total_sales
            FROM sales_orders so
            JOIN customers c ON so.customer_id = c.id
            GROUP BY c.id, c.name
            ORDER BY total_sales DESC
        ");
        return $this->db->resultSet();
    }

    public function getInventoryReportData() {
        $this->db->query("
            SELECT
                i.item_code,
                i.description,
                i.quantity_on_hand,
                i.unit_price,
                (i.quantity_on_hand * i.unit_price) as stock_value,
                c.name as category_name
            FROM inventory_items i
            LEFT JOIN item_categories c ON i.category_id = c.id
            ORDER BY stock_value DESC
        ");
        return $this->db->resultSet();
    }
}
