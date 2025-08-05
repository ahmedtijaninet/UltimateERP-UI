<?php

require_once 'modules/inventory/models/InventoryModel.php';

class InventoryController {

    private $inventory_model;

    public function __construct() {
        $this->inventory_model = new InventoryModel();
    }

    /**
     * Main entry point for the inventory module.
     * Displays the inventory dashboard.
     */
    public function index() {
        $page_title = 'Inventory Dashboard';
        require_once 'modules/inventory/inventory_dashboard_view.php';
    }

    // == ITEM METHODS ==

    public function items() {
        $page_title = 'Inventory Items';
        $items = $this->inventory_model->getItems();
        require_once 'modules/inventory/items_view.php';
    }

    public function add_item() {
        $page_title = 'Add New Item';
        $categories = $this->inventory_model->getCategories();
        $suppliers = $this->inventory_model->getSuppliers();
        require_once 'modules/inventory/add_item_view.php';
    }

    public function process_add_item() {
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
                $_SESSION['error_message'] = 'Item Code and Description are required.';
                redirect('inventory/add_item');
                return;
            }

            if ($this->inventory_model->addItem($data)) {
                $_SESSION['success_message'] = 'Item added successfully.';
                redirect('inventory/items');
            } else {
                $_SESSION['error_message'] = 'Failed to add item. The Item Code may already exist.';
                redirect('inventory/add_item');
            }
        } else {
            redirect('inventory/add_item');
        }
    }

    // == CATEGORY METHODS ==

    public function categories() {
        $page_title = 'Item Categories';
        $categories = $this->inventory_model->getCategories();
        require_once 'modules/inventory/categories_view.php';
    }

    public function add_category() {
        $page_title = 'Add New Category';
        $categories = $this->inventory_model->getCategories();
        require_once 'modules/inventory/add_category_view.php';
    }

    public function process_add_category() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'parent_category_id' => !empty($_POST['parent_category_id']) ? $_POST['parent_category_id'] : null
            ];

            if (empty($data['name'])) {
                $_SESSION['error_message'] = 'Category Name is required.';
                redirect('inventory/add_category');
                return;
            }

            if ($this->inventory_model->addCategory($data)) {
                $_SESSION['success_message'] = 'Category added successfully.';
                redirect('inventory/categories');
            } else {
                $_SESSION['error_message'] = 'Failed to add category.';
                redirect('inventory/add_category');
            }
        } else {
            redirect('inventory/add_category');
        }
    }

    // == SUPPLIER METHODS ==

    public function suppliers() {
        $page_title = 'Suppliers';
        $suppliers = $this->inventory_model->getSuppliers();
        require_once 'modules/inventory/suppliers_view.php';
    }

    public function add_supplier() {
        $page_title = 'Add New Supplier';
        require_once 'modules/inventory/add_supplier_view.php';
    }

    public function process_add_supplier() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'contact_person' => trim($_POST['contact_person']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address'])
            ];

            if (empty($data['name'])) {
                $_SESSION['error_message'] = 'Supplier Name is required.';
                redirect('inventory/add_supplier');
                return;
            }

            if ($this->inventory_model->addSupplier($data)) {
                $_SESSION['success_message'] = 'Supplier added successfully.';
                redirect('inventory/suppliers');
            } else {
                $_SESSION['error_message'] = 'Failed to add supplier.';
                redirect('inventory/add_supplier');
            }
        } else {
            redirect('inventory/add_supplier');
        }
    }
}
