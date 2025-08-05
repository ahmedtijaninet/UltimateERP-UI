<?php

require_once 'modules/finance/models/FinanceModel.php';

class FinanceController {

    private $finance_model;

    public function __construct() {
        $this->finance_model = new FinanceModel();
    }

    /**
     * Main entry point for the finance module.
     * Displays the finance dashboard.
     */
    public function index() {
        $page_title = 'Finance Dashboard';
        require_once 'modules/finance/finance_dashboard_view.php';
    }

    /**
     * Display the chart of accounts.
     */
    public function accounts() {
        $page_title = 'Chart of Accounts';
        $accounts = $this->finance_model->getAccounts();
        require_once 'modules/finance/accounts_view.php';
    }

    /**
     * Display the form to add a new account.
     */
    public function add_account() {
        $page_title = 'Add New Account';
        require_once 'modules/finance/add_account_view.php';
    }

    /**
     * Process the submission of the new account form.
     */
    public function process_add_account() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Basic validation
            $data = [
                'account_code' => trim($_POST['account_code']),
                'account_name' => trim($_POST['account_name']),
                'account_type' => $_POST['account_type'],
                'description' => trim($_POST['description'])
            ];

            if (empty($data['account_code']) || empty($data['account_name']) || empty($data['account_type'])) {
                $_SESSION['error_message'] = 'Account Code, Name, and Type are required.';
                redirect('finance/add_account');
                return;
            }

            if ($this->finance_model->addAccount($data)) {
                $_SESSION['success_message'] = 'Account added successfully.';
                redirect('finance/accounts');
            } else {
                $_SESSION['error_message'] = 'Failed to add account. The Account Code may already exist.';
                redirect('finance/add_account');
            }
        } else {
            redirect('finance/add_account');
        }
    }

    // == INVOICE METHODS ==

    public function invoices() {
        $page_title = 'Invoices';
        $invoices = $this->finance_model->getInvoices();
        require_once 'modules/finance/invoices_view.php';
    }

    public function create_invoice() {
        $page_title = 'Create New Invoice';
        $customers = $this->finance_model->getCustomers();
        require_once 'modules/finance/create_invoice_view.php';
    }

    public function process_create_invoice() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Complex validation would be needed here in a real app
            $data = [
                'customer_id' => $_POST['customer_id'],
                'issue_date' => $_POST['issue_date'],
                'due_date' => $_POST['due_date'],
                'invoice_number' => 'INV-' . time(), // Simple unique number
                'items' => [],
                'total_amount' => 0
            ];

            for ($i = 0; $i < count($_POST['items']['description']); $i++) {
                $qty = (int)$_POST['items']['quantity'][$i];
                $price = (float)$_POST['items']['unit_price'][$i];
                $total = $qty * $price;

                if ($qty > 0) {
                    $data['items'][] = [
                        'description' => $_POST['items']['description'][$i],
                        'quantity' => $qty,
                        'unit_price' => $price
                    ];
                    $data['total_amount'] += $total;
                }
            }

            if (empty($data['customer_id']) || empty($data['items'])) {
                $_SESSION['error_message'] = 'Customer and at least one item are required.';
                redirect('finance/create_invoice');
                return;
            }

            $invoice_id = $this->finance_model->createInvoice($data);
            if ($invoice_id) {
                $_SESSION['success_message'] = 'Invoice created successfully.';
                redirect('finance/view_invoice/' . $invoice_id);
            } else {
                $_SESSION['error_message'] = 'Failed to create invoice.';
                redirect('finance/create_invoice');
            }
        } else {
            redirect('finance/create_invoice');
        }
    }

    public function view_invoice($id) {
        $page_title = 'View Invoice';
        $invoice = $this->finance_model->getInvoiceById($id);
        if (!$invoice) {
            // Handle not found
            redirect('finance/invoices');
            return;
        }
        $payments = $this->finance_model->getPaymentsForInvoice($id);
        require_once 'modules/finance/view_invoice_view.php';
    }

    // == PAYMENT METHODS ==

    public function process_add_payment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $invoice_id = $_POST['invoice_id'];
            $data = [
                'invoice_id' => $invoice_id,
                'payment_date' => $_POST['payment_date'],
                'amount' => $_POST['amount'],
                'payment_method' => $_POST['payment_method'],
                'notes' => trim($_POST['notes'])
            ];

            if (empty($data['payment_date']) || empty($data['amount']) || $data['amount'] <= 0) {
                $_SESSION['error_message'] = 'Payment Date and a valid Amount are required.';
                redirect('finance/view_invoice/' . $invoice_id);
                return;
            }

            if ($this->finance_model->addPayment($data)) {
                $_SESSION['success_message'] = 'Payment added successfully.';
            } else {
                $_SESSION['error_message'] = 'Failed to add payment.';
            }
            redirect('finance/view_invoice/' . $invoice_id);
        } else {
            // Redirect to main invoices list if accessed directly
            redirect('finance/invoices');
        }
    }
}
