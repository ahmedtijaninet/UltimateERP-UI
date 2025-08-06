<?php

require_once 'includes/BaseModel.php';

require_once 'modules/inventory/models/InventoryModel.php';

class SalesModel extends BaseModel {

    private $inventory_model;

    public function __construct() {
        parent::__construct();
        $this->inventory_model = new InventoryModel();
    }

    // == CUSTOMER METHODS ==
    public function getCustomers($search = null) {
        $sql = "SELECT * FROM customers";
        if ($search) {
            $sql .= " WHERE name LIKE :search OR email LIKE :search";
        }
        $sql .= " ORDER BY name ASC";

        $this->db->query($sql);

        if ($search) {
            $this->db->bind(':search', '%' . $search . '%');
        }

        return $this->db->resultSet();
    }

    public function getCustomerById($id) {
        $this->db->query("SELECT * FROM customers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addCustomer($data) {
        $this->db->query("INSERT INTO customers (name, contact_person, email, phone, address) VALUES (:name, :contact_person, :email, :phone, :address)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        return $this->db->execute();
    }

    public function updateCustomer($data) {
        $this->db->query("UPDATE customers SET name = :name, contact_person = :contact_person, email = :email, phone = :phone, address = :address WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        return $this->db->execute();
    }

    public function deleteCustomer($id) {
        // Note: You might want to handle what happens to orders from this customer
        $this->db->query("DELETE FROM customers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // == SALES ORDER METHODS ==
    public function getSalesOrders() {
        $this->db->query("
            SELECT so.*, c.name as customer_name
            FROM sales_orders so
            JOIN customers c ON so.customer_id = c.id
            ORDER BY so.order_date DESC
        ");
        return $this->db->resultSet();
    }

    public function getSalesOrderById($id) {
        $this->db->query("
            SELECT so.*, c.name as customer_name, c.address as customer_address
            FROM sales_orders so
            JOIN customers c ON so.customer_id = c.id
            WHERE so.id = :id
        ");
        $this->db->bind(':id', $id);
        $so = $this->db->single();

        if ($so) {
            $this->db->query("SELECT * FROM sales_order_items WHERE so_id = :id");
            $this->db->bind(':id', $id);
            $so['items'] = $this->db->resultSet();
        }

        return $so;
    }

    public function cancelSalesOrder($id) {
        $this->db->query("UPDATE sales_orders SET status = 'Cancelled' WHERE id = :id AND status NOT IN ('Shipped', 'Cancelled')");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function shipSalesOrder($id) {
        $so = $this->getSalesOrderById($id);
        if (!$so || $so['status'] != 'Confirmed') {
            return false;
        }

        // This should be a transaction
        foreach ($so['items'] as $item) {
            $this->inventory_model->adjustStock($item['item_id'], -$item['quantity'], 'Sale', 'SO: ' . $so['so_number'], $id);
        }

        $this->db->query("UPDATE sales_orders SET status = 'Shipped' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function createSalesOrder($data) {
        $this->db->query("
            INSERT INTO sales_orders (so_number, customer_id, order_date, total_amount, created_by_user_id)
            VALUES (:so_number, :customer_id, :order_date, :total_amount, :created_by_user_id)
        ");
        $this->db->bind(':so_number', $data['so_number']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':order_date', $data['order_date']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':created_by_user_id', $_SESSION['user_id']);

        if ($this->db->execute()) {
            $so_id = $this->db->lastInsertId();

            foreach ($data['items'] as $item) {
                $this->db->query("
                    INSERT INTO sales_order_items (so_id, item_id, description, quantity, unit_price, total)
                    VALUES (:so_id, :item_id, :description, :quantity, :unit_price, :total)
                ");
                $this->db->bind(':so_id', $so_id);
                $this->db->bind(':item_id', $item['id']);
                $this->db->bind(':description', $item['description']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total', $item['quantity'] * $item['unit_price']);

                if (!$this->db->execute()) return false;
            }
            return $so_id;
        }
        return false;
    }

    // == HELPERS ==
    public function getItems() {
        $this->db->query("SELECT id, item_code, description, unit_price FROM inventory_items ORDER BY item_code ASC");
        return $this->db->resultSet();
    }
}
