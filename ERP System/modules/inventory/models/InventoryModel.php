<?php

require_once 'includes/BaseModel.php';

class InventoryModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }

    // == ITEM METHODS ==
    public function getItems($search = null) {
        $sql = "
            SELECT i.*, c.name as category_name, s.name as supplier_name
            FROM inventory_items i
            LEFT JOIN item_categories c ON i.category_id = c.id
            LEFT JOIN suppliers s ON i.supplier_id = s.id
        ";
        if ($search) {
            $sql .= " WHERE i.item_code LIKE :search OR i.description LIKE :search";
        }
        $sql .= " ORDER BY i.item_code ASC";

        $this->db->query($sql);

        if ($search) {
            $this->db->bind(':search', '%' . $search . '%');
        }

        return $this->db->resultSet();
    }

    public function getItemById($id) {
        $this->db->query("SELECT * FROM inventory_items WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addItem($data) {
        $this->db->query("
            INSERT INTO inventory_items (item_code, description, category_id, unit_price, quantity_on_hand, reorder_level, supplier_id)
            VALUES (:item_code, :description, :category_id, :unit_price, :quantity_on_hand, :reorder_level, :supplier_id)
        ");
        $this->db->bind(':item_code', $data['item_code']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':unit_price', $data['unit_price']);
        $this->db->bind(':quantity_on_hand', $data['quantity_on_hand']);
        $this->db->bind(':reorder_level', $data['reorder_level']);
        $this->db->bind(':supplier_id', $data['supplier_id']);

        return $this->db->execute();
    }

    public function updateItem($data) {
        $this->db->query("
            UPDATE inventory_items
            SET item_code = :item_code, description = :description, category_id = :category_id, unit_price = :unit_price, reorder_level = :reorder_level, supplier_id = :supplier_id
            WHERE id = :id
        ");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':item_code', $data['item_code']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':unit_price', $data['unit_price']);
        $this->db->bind(':reorder_level', $data['reorder_level']);
        $this->db->bind(':supplier_id', $data['supplier_id']);

        return $this->db->execute();
    }

    public function deleteItem($id) {
        $this->db->query("DELETE FROM inventory_items WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // == CATEGORY METHODS ==
    public function getCategories() {
        $this->db->query("SELECT * FROM item_categories ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query("SELECT * FROM item_categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addCategory($data) {
        $this->db->query("INSERT INTO item_categories (name, description, parent_category_id) VALUES (:name, :description, :parent_category_id)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':parent_category_id', $data['parent_category_id']);
        return $this->db->execute();
    }

    public function updateCategory($data) {
        $this->db->query("UPDATE item_categories SET name = :name, description = :description, parent_category_id = :parent_category_id WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':parent_category_id', $data['parent_category_id']);
        return $this->db->execute();
    }

    public function deleteCategory($id) {
        // Note: You might want to handle what happens to items in this category
        $this->db->query("DELETE FROM item_categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // == SUPPLIER METHODS ==
    public function getSuppliers() {
        $this->db->query("SELECT * FROM suppliers ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getSupplierById($id) {
        $this->db->query("SELECT * FROM suppliers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addSupplier($data) {
        $this->db->query("INSERT INTO suppliers (name, contact_person, email, phone, address) VALUES (:name, :contact_person, :email, :phone, :address)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        return $this->db->execute();
    }

    public function updateSupplier($data) {
        $this->db->query("UPDATE suppliers SET name = :name, contact_person = :contact_person, email = :email, phone = :phone, address = :address WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':contact_person', $data['contact_person']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        return $this->db->execute();
    }

    public function deleteSupplier($id) {
        // Note: You might want to handle what happens to items from this supplier
        $this->db->query("DELETE FROM suppliers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // == STOCK METHODS ==

    public function getStockTransactionsForItem($item_id) {
        $this->db->query("
            SELECT *
            FROM inventory_transactions
            WHERE item_id = :item_id
            ORDER BY created_at DESC
        ");
        $this->db->bind(':item_id', $item_id);
        return $this->db->resultSet();
    }

    public function adjustStock($item_id, $quantity_change, $type, $notes = '', $related_document_id = null) {
        // This should be a transaction
        // 1. Update the item's quantity
        $this->db->query("
            UPDATE inventory_items
            SET quantity_on_hand = quantity_on_hand + :quantity_change
            WHERE id = :item_id
        ");
        $this->db->bind(':quantity_change', $quantity_change);
        $this->db->bind(':item_id', $item_id);

        if (!$this->db->execute()) {
            return false;
        }

        // 2. Log the transaction
        $this->db->query("
            INSERT INTO inventory_transactions (item_id, transaction_type, quantity_change, related_document_id, notes, created_by_user_id)
            VALUES (:item_id, :transaction_type, :quantity_change, :related_document_id, :notes, :created_by_user_id)
        ");
        $this->db->bind(':item_id', $item_id);
        $this->db->bind(':transaction_type', $type);
        $this->db->bind(':quantity_change', $quantity_change);
        $this->db->bind(':related_document_id', $related_document_id);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':created_by_user_id', $_SESSION['user_id']);

        return $this->db->execute();
    }
}
