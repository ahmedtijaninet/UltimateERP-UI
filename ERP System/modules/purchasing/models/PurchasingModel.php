<?php

require_once 'includes/BaseModel.php';

require_once 'modules/inventory/models/InventoryModel.php';

class PurchasingModel extends BaseModel {

    private $inventory_model;

    public function __construct() {
        parent::__construct();
        $this->inventory_model = new InventoryModel();
    }

    // == PURCHASE ORDER METHODS ==
    public function getPurchaseOrders() {
        $this->db->query("
            SELECT po.*, s.name as supplier_name
            FROM purchase_orders po
            JOIN suppliers s ON po.supplier_id = s.id
            ORDER BY po.order_date DESC
        ");
        return $this->db->resultSet();
    }

    public function getPurchaseOrderById($id) {
        $this->db->query("
            SELECT po.*, s.name as supplier_name, s.address as supplier_address
            FROM purchase_orders po
            JOIN suppliers s ON po.supplier_id = s.id
            WHERE po.id = :id
        ");
        $this->db->bind(':id', $id);
        $po = $this->db->single();

        if ($po) {
            $this->db->query("SELECT * FROM purchase_order_items WHERE po_id = :id");
            $this->db->bind(':id', $id);
            $po['items'] = $this->db->resultSet();
        }

        return $po;
    }

    public function cancelPurchaseOrder($id) {
        $this->db->query("UPDATE purchase_orders SET status = 'Cancelled' WHERE id = :id AND status NOT IN ('Fully Received', 'Cancelled')");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function receivePurchaseOrder($id) {
        $po = $this->getPurchaseOrderById($id);
        if (!$po || $po['status'] != 'Ordered') {
            return false;
        }

        // This should be a transaction
        foreach ($po['items'] as $item) {
            $this->inventory_model->adjustStock($item['item_id'], $item['quantity'], 'Purchase', 'PO: ' . $po['po_number'], $id);
        }

        $this->db->query("UPDATE purchase_orders SET status = 'Fully Received' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function createPurchaseOrder($data) {
        $this->db->query("
            INSERT INTO purchase_orders (po_number, supplier_id, order_date, expected_delivery_date, total_amount, created_by_user_id)
            VALUES (:po_number, :supplier_id, :order_date, :expected_delivery_date, :total_amount, :created_by_user_id)
        ");
        $this->db->bind(':po_number', $data['po_number']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':order_date', $data['order_date']);
        $this->db->bind(':expected_delivery_date', $data['expected_delivery_date']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':created_by_user_id', $_SESSION['user_id']);

        if ($this->db->execute()) {
            $po_id = $this->db->lastInsertId();

            foreach ($data['items'] as $item) {
                $this->db->query("
                    INSERT INTO purchase_order_items (po_id, item_id, description, quantity, unit_price, total)
                    VALUES (:po_id, :item_id, :description, :quantity, :unit_price, :total)
                ");
                $this->db->bind(':po_id', $po_id);
                $this->db->bind(':item_id', $item['id']);
                $this->db->bind(':description', $item['description']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total', $item['quantity'] * $item['unit_price']);

                if (!$this->db->execute()) return false;
            }
            return $po_id;
        }
        return false;
    }

    // == HELPERS ==
    public function getSuppliers() {
        $this->db->query("SELECT id, name FROM suppliers ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getItems() {
        $this->db->query("SELECT id, item_code, description, unit_price FROM inventory_items ORDER BY item_code ASC");
        return $this->db->resultSet();
    }
}
