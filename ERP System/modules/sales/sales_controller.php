<?php

require_once 'modules/sales/models/SalesModel.php';

class SalesController {

    private $sales_model;

    public function __construct() {
        $this->sales_model = new SalesModel();
    }

    /**
     * Main entry point for the sales module.
     * Displays the sales dashboard.
     */
    public function index() {
        $page_title = 'Sales Dashboard';
        require_once 'modules/sales/sales_dashboard_view.php';
    }

    // == CUSTOMER METHODS ==

    public function customers() {
        $page_title = 'Customers';
        $customers = $this->sales_model->getCustomers();
        require_once 'modules/sales/customers_view.php';
    }

    public function add_customer() {
        $page_title = 'Add New Customer';
        require_once 'modules/sales/add_customer_view.php';
    }

    public function process_add_customer() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => trim($_POST['name']),
                'contact_person' => trim($_POST['contact_person']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address'])
            ];

            if (empty($data['name'])) {
                $_SESSION['error_message'] = 'Customer Name is required.';
                redirect('sales/add_customer');
                return;
            }

            if ($this->sales_model->addCustomer($data)) {
                $_SESSION['success_message'] = 'Customer added successfully.';
                redirect('sales/customers');
            } else {
                $_SESSION['error_message'] = 'Failed to add customer.';
                redirect('sales/add_customer');
            }
        } else {
            redirect('sales/add_customer');
        }
    }

    // == SALES ORDER METHODS ==

    public function sales_orders() {
        $page_title = 'Sales Orders';
        $sales_orders = $this->sales_model->getSalesOrders();
        require_once 'modules/sales/sales_orders_view.php';
    }

    public function create_so() {
        $page_title = 'Create New Sales Order';
        $customers = $this->sales_model->getCustomers();
        $items = $this->sales_model->getItems();
        require_once 'modules/sales/create_so_view.php';
    }

    public function process_create_so() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'customer_id' => $_POST['customer_id'],
                'order_date' => $_POST['order_date'],
                'so_number' => 'SO-' . time(),
                'items' => [],
                'total_amount' => 0
            ];

            for ($i = 0; $i < count($_POST['items']['id']); $i++) {
                $qty = (int)$_POST['items']['quantity'][$i];
                $price = (float)$_POST['items']['unit_price'][$i];
                if ($qty > 0) {
                    $item_data = [
                        'id' => $_POST['items']['id'][$i],
                        'description' => $_POST['items']['description'][$i],
                        'quantity' => $qty,
                        'unit_price' => $price
                    ];
                    $data['items'][] = $item_data;
                    $data['total_amount'] += $qty * $price;
                }
            }

            if (empty($data['customer_id']) || empty($data['items'])) {
                $_SESSION['error_message'] = 'Customer and at least one item are required.';
                redirect('sales/create_so');
                return;
            }

            $so_id = $this->sales_model->createSalesOrder($data);
            if ($so_id) {
                $_SESSION['success_message'] = 'Sales Order created successfully.';
                redirect('sales/view_so/' . $so_id);
            } else {
                $_SESSION['error_message'] = 'Failed to create Sales Order.';
                redirect('sales/create_so');
            }
        } else {
            redirect('sales/create_so');
        }
    }

    public function view_so($id) {
        $page_title = 'View Sales Order';
        $so = $this->sales_model->getSalesOrderById($id);
        if (!$so) {
            redirect('sales/sales_orders');
            return;
        }
        require_once 'modules/sales/view_so_view.php';
    }
}
