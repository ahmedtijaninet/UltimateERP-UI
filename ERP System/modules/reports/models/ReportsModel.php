<?php

require_once 'includes/BaseModel.php';

class ReportsModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // == REPORTING METHODS (to be implemented) ==

    public function getSalesReportData($start_date = null, $end_date = null) {
        $sql = "
            SELECT
                c.name as customer_name,
                COUNT(so.id) as total_orders,
                SUM(so.total_amount) as total_sales
            FROM sales_orders so
            JOIN customers c ON so.customer_id = c.id
        ";
        $where = [];
        if ($start_date) {
            $where[] = "so.order_date >= :start_date";
        }
        if ($end_date) {
            $where[] = "so.order_date <= :end_date";
        }
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " GROUP BY c.id, c.name ORDER BY total_sales DESC";

        $this->db->query($sql);

        if ($start_date) {
            $this->db->bind(':start_date', $start_date);
        }
        if ($end_date) {
            $this->db->bind(':end_date', $end_date);
        }

        return $this->db->resultSet();
    }

    public function getInventoryReportData($category_id = null) {
        $sql = "
            SELECT
                i.item_code,
                i.description,
                i.quantity_on_hand,
                i.unit_price,
                (i.quantity_on_hand * i.unit_price) as stock_value,
                c.name as category_name
            FROM inventory_items i
            LEFT JOIN item_categories c ON i.category_id = c.id
        ";
        if ($category_id) {
            $sql .= " WHERE i.category_id = :category_id";
        }
        $sql .= " ORDER BY stock_value DESC";

        $this->db->query($sql);

        if ($category_id) {
            $this->db->bind(':category_id', $category_id);
        }

        return $this->db->resultSet();
    }

    public function getCategories() {
        $this->db->query("SELECT id, name FROM item_categories ORDER BY name ASC");
        return $this->db->resultSet();
    }
}
