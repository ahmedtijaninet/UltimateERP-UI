<?php

require_once 'modules/inventory/models/InventoryModel.php';

class InventoryController {

    private $inventory_model;
    private $auth_service;

    public function __construct() {
        $this->inventory_model = new InventoryModel();
        $this->auth_service = new AuthService();
    }

    /**
     * Main entry point for the inventory module.
     * Displays the inventory dashboard.
     */
    public function index() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Inventory Dashboard';
        require_once 'modules/inventory/inventory_dashboard_view.php';
    }

    // == ITEM METHODS ==

    public function items() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Inventory Items';
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $items = $this->inventory_model->getItems($search);
        require_once 'modules/inventory/items_view.php';
    }

    public function view_item($id) {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'View Item';
        $item = $this->inventory_model->getItemById($id);
        if (!$item) {
            redirect('inventory/items');
            return;
        }
        $transactions = $this->inventory_model->getStockTransactionsForItem($id);
        require_once 'modules/inventory/view_item_view.php';
    }

    public function process_stock_adjustment() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_id = $_POST['item_id'];
            $quantity_change = (int)$_POST['quantity_change'];
            $notes = trim($_POST['notes']);

            if (empty($quantity_change)) {
                setToastMessage('Quantity change cannot be zero or empty.', 'error');
                redirect('inventory/view_item/' . $item_id);
                return;
            }

            if ($this->inventory_model->adjustStock($item_id, $quantity_change, 'Adjustment', $notes)) {
                $this->auth_service->audit('stock_adjustment', 'item', $item_id, "Adjusted by {$quantity_change}");
                setToastMessage('Stock adjusted successfully.', 'success');
            } else {
                setToastMessage('Failed to adjust stock.', 'error');
            }
            redirect('inventory/view_item/' . $item_id);
        } else {
            redirect('inventory/items');
        }
    }

    public function add_item() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/items');
        }
        $page_title = 'Add New Item';
        $categories = $this->inventory_model->getCategories();
        $suppliers = $this->inventory_model->getSuppliers();
        require_once 'modules/inventory/add_item_view.php';
    }

    public function edit_item($id) {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/items');
        }
        $page_title = 'Edit Item';
        $item = $this->inventory_model->getItemById($id);
        if (!$item) {
            redirect('inventory/items');
            return;
        }
        $categories = $this->inventory_model->getCategories();
        $suppliers = $this->inventory_model->getSuppliers();
        require_once 'modules/inventory/edit_item_view.php';
    }

    public function process_update_item() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/items');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $data = [
                'id' => $id,
                'item_code' => trim($_POST['item_code']),
                'description' => trim($_POST['description']),
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'supplier_id' => !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : null,
                'unit_price' => !empty($_POST['unit_price']) ? $_POST['unit_price'] : 0,
                'reorder_level' => !empty($_POST['reorder_level']) ? $_POST['reorder_level'] : 0
            ];

            if (empty($data['item_code']) || empty($data['description'])) {
                setToastMessage('Item Code and Description are required.', 'error');
                redirect('inventory/edit_item/' . $id);
                return;
            }

            if ($this->inventory_model->updateItem($data)) {
                $this->auth_service->audit('update_item', 'item', $id);
                setToastMessage('Item updated successfully.', 'success');
                redirect('inventory/items');
            } else {
                setToastMessage('Failed to update item.', 'error');
                redirect('inventory/edit_item/' . $id);
            }
        } else {
            redirect('inventory/items');
        }
    }

    public function delete_item($id) {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/items');
        }
        if ($this->inventory_model->deleteItem($id)) {
            $this->auth_service->audit('delete_item', 'item', $id);
            setToastMessage('Item deleted successfully.', 'success');
        } else {
            setToastMessage('Failed to delete item.', 'error');
        }
        redirect('inventory/items');
    }

    public function process_add_item() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/items');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'item_code' => trim($_POST['item_code']),
                'description' => trim($_POST['description']),
                'category_id' => !empty($_POST['category_id']) ? $_POST['category_id'] : null,
                'supplier_id' => !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : null,
                'unit_price' => !empty($_POST['unit_price']) ? $_POST['unit_price'] : 0,
                'quantity_on_hand' => !empty($_POST['quantity_on_hand']) ? $_POST['quantity_on_hand'] : 0,
                'reorder_level' => !empty($_POST['reorder_level']) ? $_POST['reorder_level'] : 0
            ];

            if (empty($data['item_code']) || empty($data['description'])) {
                setToastMessage('Item Code and Description are required.', 'error');
                redirect('inventory/add_item');
                return;
            }

            if ($this->inventory_model->addItem($data)) {
                $this->auth_service->audit('add_item', 'item', $this->inventory_model->db->lastInsertId());
                setToastMessage('Item added successfully.', 'success');
                redirect('inventory/items');
            } else {
                setToastMessage('Failed to add item. The Item Code may already exist.', 'error');
                redirect('inventory/add_item');
            }
        } else {
            redirect('inventory/add_item');
        }
    }

    // == CATEGORY METHODS ==

    public function categories() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Item Categories';
        $categories = $this->inventory_model->getCategories();
        require_once 'modules/inventory/categories_view.php';
    }

    public function add_category() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/categories');
        }
        $page_title = 'Add New Category';
        $categories = $this->inventory_model->getCategories();
        require_once 'modules/inventory/add_category_view.php';
    }

    public function edit_category($id) {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/categories');
        }
        $page_title = 'Edit Category';
        $category = $this->inventory_model->getCategoryById($id);
        if (!$category) {
            redirect('inventory/categories');
            return;
        }
        $categories = $this->inventory_model->getCategories();
        require_once 'modules/inventory/edit_category_view.php';
    }

    public function process_update_category() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/categories');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'parent_category_id' => !empty($_POST['parent_category_id']) ? $_POST['parent_category_id'] : null
            ];

            if (empty($data['name'])) {
                setToastMessage('Category Name is required.', 'error');
                redirect('inventory/edit_category/' . $id);
                return;
            }

            if ($this->inventory_model->updateCategory($data)) {
                $this->auth_service->audit('update_category', 'category', $id);
                setToastMessage('Category updated successfully.', 'success');
                redirect('inventory/categories');
            } else {
                setToastMessage('Failed to update category.', 'error');
                redirect('inventory/edit_category/' . $id);
            }
        } else {
            redirect('inventory/categories');
        }
    }

    public function delete_category($id) {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/categories');
        }
        if ($this->inventory_model->deleteCategory($id)) {
            $this->auth_service->audit('delete_category', 'category', $id);
            setToastMessage('Category deleted successfully.', 'success');
        } else {
            setToastMessage('Failed to delete category. It may be in use.', 'error');
        }
        redirect('inventory/categories');
    }

    public function process_add_category() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/categories');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'parent_category_id' => !empty($_POST['parent_category_id']) ? $_POST['parent_category_id'] : null
            ];

            if (empty($data['name'])) {
                setToastMessage('Category Name is required.', 'error');
                redirect('inventory/add_category');
                return;
            }

            if ($this->inventory_model->addCategory($data)) {
                $this->auth_service->audit('add_category', 'category', $this->inventory_model->db->lastInsertId());
                setToastMessage('Category added successfully.', 'success');
                redirect('inventory/categories');
            } else {
                setToastMessage('Failed to add category.', 'error');
                redirect('inventory/add_category');
            }
        } else {
            redirect('inventory/add_category');
        }
    }

    // == SUPPLIER METHODS ==

    public function suppliers() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Suppliers';
        $suppliers = $this->inventory_model->getSuppliers();
        require_once 'modules/inventory/suppliers_view.php';
    }

    public function add_supplier() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/suppliers');
        }
        $page_title = 'Add New Supplier';
        require_once 'modules/inventory/add_supplier_view.php';
    }

    public function edit_supplier($id) {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/suppliers');
        }
        $page_title = 'Edit Supplier';
        $supplier = $this->inventory_model->getSupplierById($id);
        if (!$supplier) {
            redirect('inventory/suppliers');
            return;
        }
        require_once 'modules/inventory/edit_supplier_view.php';
    }

    public function process_update_supplier() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/suppliers');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'contact_person' => trim($_POST['contact_person']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address'])
            ];

            if (empty($data['name'])) {
                setToastMessage('Supplier Name is required.', 'error');
                redirect('inventory/edit_supplier/' . $id);
                return;
            }

            if ($this->inventory_model->updateSupplier($data)) {
                $this->auth_service->audit('update_supplier', 'supplier', $id);
                setToastMessage('Supplier updated successfully.', 'success');
                redirect('inventory/suppliers');
            } else {
                setToastMessage('Failed to update supplier.', 'error');
                redirect('inventory/edit_supplier/' . $id);
            }
        } else {
            redirect('inventory/suppliers');
        }
    }

    public function delete_supplier($id) {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/suppliers');
        }
        if ($this->inventory_model->deleteSupplier($id)) {
            $this->auth_service->audit('delete_supplier', 'supplier', $id);
            setToastMessage('Supplier deleted successfully.', 'success');
        } else {
            setToastMessage('Failed to delete supplier. It may be in use.', 'error');
        }
        redirect('inventory/suppliers');
    }

    public function process_add_supplier() {
        if (!$this->auth_service->hasPermission('manage_inventory')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('inventory/suppliers');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'contact_person' => trim($_POST['contact_person']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address'])
            ];

            if (empty($data['name'])) {
                setToastMessage('Supplier Name is required.', 'error');
                redirect('inventory/add_supplier');
                return;
            }

            if ($this->inventory_model->addSupplier($data)) {
                $this->auth_service->audit('add_supplier', 'supplier', $this->inventory_model->db->lastInsertId());
                setToastMessage('Supplier added successfully.', 'success');
                redirect('inventory/suppliers');
            } else {
                setToastMessage('Failed to add supplier.', 'error');
                redirect('inventory/add_supplier');
            }
        } else {
            redirect('inventory/add_supplier');
        }
    }
}
