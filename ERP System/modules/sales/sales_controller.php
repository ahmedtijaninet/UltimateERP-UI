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
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $customers = $this->sales_model->getCustomers($search);
        require_once 'modules/sales/customers_view.php';
    }

    public function add_customer() {
        $page_title = 'Add New Customer';
        require_once 'modules/sales/add_customer_view.php';
    }

    public function edit_customer($id) {
        $page_title = 'Edit Customer';
        $customer = $this->sales_model->getCustomerById($id);
        if (!$customer) {
            redirect('sales/customers');
            return;
        }
        require_once 'modules/sales/edit_customer_view.php';
    }

    public function process_update_customer() {
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
                setToastMessage('Customer Name is required.', 'error');
                redirect('sales/edit_customer/' . $id);
                return;
            }

            if ($this->sales_model->updateCustomer($data)) {
                setToastMessage('Customer updated successfully.', 'success');
                redirect('sales/customers');
            } else {
                setToastMessage('Failed to update customer.', 'error');
                redirect('sales/edit_customer/' . $id);
            }
        } else {
            redirect('sales/customers');
        }
    }

    public function delete_customer($id) {
        if ($this->sales_model->deleteCustomer($id)) {
            setToastMessage('Customer deleted successfully.', 'success');
        } else {
            setToastMessage('Failed to delete customer. It may be in use.', 'error');
        }
        redirect('sales/customers');
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
                setToastMessage('Customer Name is required.', 'error');
                redirect('sales/add_customer');
                return;
            }

            if ($this->sales_model->addCustomer($data)) {
                setToastMessage('Customer added successfully.', 'success');
                redirect('sales/customers');
            } else {
                setToastMessage('Failed to add customer.', 'error');
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
                setToastMessage('Customer and at least one item are required.', 'error');
                redirect('sales/create_so');
                return;
            }

            $so_id = $this->sales_model->createSalesOrder($data);
            if ($so_id) {
                setToastMessage('Sales Order created successfully.', 'success');
                redirect('sales/view_so/' . $so_id);
            } else {
                setToastMessage('Failed to create Sales Order.', 'error');
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

    public function cancel_so($id) {
        if ($this->sales_model->cancelSalesOrder($id)) {
            setToastMessage('Sales Order cancelled successfully.', 'success');
        } else {
            setToastMessage('Failed to cancel Sales Order. It may have already been shipped or cancelled.', 'error');
        }
        redirect('sales/sales_orders');
    }

    public function confirm_so($id) {
        // In a real app, you'd check for stock availability here first
        $this->sales_model->db->query("UPDATE sales_orders SET status = 'Confirmed' WHERE id = :id AND status = 'Draft'");
        $this->sales_model->db->bind(':id', $id);
        if ($this->sales_model->db->execute()) {
            setToastMessage('Sales Order confirmed.', 'success');
        } else {
            setToastMessage('Failed to confirm Sales Order.', 'error');
        }
        redirect('sales/view_so/' . $id);
    }

    public function ship_order($id) {
        if ($this->sales_model->shipSalesOrder($id)) {
            setToastMessage('Sales Order marked as shipped and inventory updated.', 'success');
        } else {
            setToastMessage('Failed to ship order. It may not be in a "Confirmed" state.', 'error');
        }
        redirect('sales/view_so/' . $id);
    }
}
