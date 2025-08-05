<?php

require_once 'modules/purchasing/models/PurchasingModel.php';

class PurchasingController {

    private $purchasing_model;

    public function __construct() {
        $this->purchasing_model = new PurchasingModel();
    }

    /**
     * Main entry point for the purchasing module.
     * Displays the purchasing dashboard.
     */
    public function index() {
        $page_title = 'Purchasing Dashboard';
        require_once 'modules/purchasing/purchasing_dashboard_view.php';
    }

    // == PURCHASE ORDER METHODS ==

    public function purchase_orders() {
        $page_title = 'Purchase Orders';
        $purchase_orders = $this->purchasing_model->getPurchaseOrders();
        require_once 'modules/purchasing/purchase_orders_view.php';
    }

    public function create_po() {
        $page_title = 'Create New Purchase Order';
        $suppliers = $this->purchasing_model->getSuppliers();
        $items = $this->purchasing_model->getItems();
        require_once 'modules/purchasing/create_po_view.php';
    }

    public function process_create_po() {
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
                $_SESSION['error_message'] = 'Supplier and at least one item are required.';
                redirect('purchasing/create_po');
                return;
            }

            $po_id = $this->purchasing_model->createPurchaseOrder($data);
            if ($po_id) {
                $_SESSION['success_message'] = 'Purchase Order created successfully.';
                redirect('purchasing/view_po/' . $po_id);
            } else {
                $_SESSION['error_message'] = 'Failed to create Purchase Order.';
                redirect('purchasing/create_po');
            }
        } else {
            redirect('purchasing/create_po');
        }
    }

    public function view_po($id) {
        $page_title = 'View Purchase Order';
        $po = $this->purchasing_model->getPurchaseOrderById($id);
        if (!$po) {
            redirect('purchasing/purchase_orders');
            return;
        }
        require_once 'modules/purchasing/view_po_view.php';
    }

}
