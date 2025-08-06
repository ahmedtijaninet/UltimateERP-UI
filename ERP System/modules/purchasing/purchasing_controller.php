<?php

require_once 'modules/purchasing/models/PurchasingModel.php';

class PurchasingController {

    private $purchasing_model;
    private $auth_service;

    public function __construct() {
        $this->purchasing_model = new PurchasingModel();
        $this->auth_service = new AuthService();
    }

    /**
     * Main entry point for the purchasing module.
     * Displays the purchasing dashboard.
     */
    public function index() {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Purchasing Dashboard';
        require_once 'modules/purchasing/purchasing_dashboard_view.php';
    }

    // == PURCHASE ORDER METHODS ==

    public function purchase_orders() {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Purchase Orders';
        $purchase_orders = $this->purchasing_model->getPurchaseOrders();
        require_once 'modules/purchasing/purchase_orders_view.php';
    }

    public function create_po() {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('purchasing/purchase_orders');
        }
        $page_title = 'Create New Purchase Order';
        $suppliers = $this->purchasing_model->getSuppliers();
        $items = $this->purchasing_model->getItems();
        require_once 'modules/purchasing/create_po_view.php';
    }

    public function process_create_po() {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('purchasing/purchase_orders');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'supplier_id' => $_POST['supplier_id'],
                'order_date' => $_POST['order_date'],
                'expected_delivery_date' => !empty($_POST['expected_delivery_date']) ? $_POST['expected_delivery_date'] : null,
                'po_number' => 'PO-' . time(),
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

            if (empty($data['supplier_id']) || empty($data['items'])) {
                setToastMessage('Supplier and at least one item are required.', 'error');
                redirect('purchasing/create_po');
                return;
            }

            $po_id = $this->purchasing_model->createPurchaseOrder($data);
            if ($po_id) {
                $this->auth_service->audit('create_po', 'purchase_order', $po_id);
                setToastMessage('Purchase Order created successfully.', 'success');
                redirect('purchasing/view_po/' . $po_id);
            } else {
                setToastMessage('Failed to create Purchase Order.', 'error');
                redirect('purchasing/create_po');
            }
        } else {
            redirect('purchasing/create_po');
        }
    }

    public function view_po($id) {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'View Purchase Order';
        $po = $this->purchasing_model->getPurchaseOrderById($id);
        if (!$po) {
            redirect('purchasing/purchase_orders');
            return;
        }
        require_once 'modules/purchasing/view_po_view.php';
    }

    public function cancel_po($id) {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('purchasing/purchase_orders');
        }
        if ($this->purchasing_model->cancelPurchaseOrder($id)) {
            $this->auth_service->audit('cancel_po', 'purchase_order', $id);
            setToastMessage('Purchase Order cancelled successfully.', 'success');
        } else {
            setToastMessage('Failed to cancel Purchase Order. It may have already been received or cancelled.', 'error');
        }
        redirect('purchasing/purchase_orders');
    }

    public function order_po($id) {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('purchasing/purchase_orders');
        }
        $this->purchasing_model->db->query("UPDATE purchase_orders SET status = 'Ordered' WHERE id = :id AND status = 'Draft'");
        $this->purchasing_model->db->bind(':id', $id);
        if ($this->purchasing_model->db->execute()) {
            $this->auth_service->audit('order_po', 'purchase_order', $id);
            setToastMessage('Purchase Order status changed to Ordered.', 'success');
        } else {
            setToastMessage('Failed to update Purchase Order status.', 'error');
        }
        redirect('purchasing/view_po/' . $id);
    }

    public function receive_po($id) {
        if (!$this->auth_service->hasPermission('manage_purchasing')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('purchasing/purchase_orders');
        }
        if ($this->purchasing_model->receivePurchaseOrder($id)) {
            $this->auth_service->audit('receive_po', 'purchase_order', $id);
            setToastMessage('Purchase Order marked as received and inventory updated.', 'success');
        } else {
            setToastMessage('Failed to receive Purchase Order. It may not be in an "Ordered" state.', 'error');
        }
        redirect('purchasing/view_po/' . $id);
    }
}
