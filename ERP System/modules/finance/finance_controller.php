<?php

require_once 'modules/finance/models/FinanceModel.php';

class FinanceController {

    private $finance_model;
    private $auth_service;

    public function __construct() {
        $this->finance_model = new FinanceModel();
        $this->auth_service = new AuthService();
    }

    /**
     * Main entry point for the finance module.
     * Displays the finance dashboard.
     */
    public function index() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Finance Dashboard';
        require_once 'modules/finance/finance_dashboard_view.php';
    }

    /**
     * Display the chart of accounts.
     */
    public function accounts() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Chart of Accounts';
        $accounts = $this->finance_model->getAccounts();
        require_once 'modules/finance/accounts_view.php';
    }

    public function add_account() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/accounts');
        }
        $page_title = 'Add New Account';
        require_once 'modules/finance/add_account_view.php';
    }

    public function edit_account($id) {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/accounts');
        }
        $page_title = 'Edit Account';
        $account = $this->finance_model->getAccountById($id);
        if (!$account) {
            redirect('finance/accounts');
            return;
        }
        require_once 'modules/finance/edit_account_view.php';
    }

    public function process_update_account() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/accounts');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $data = [
                'id' => $id,
                'account_code' => trim($_POST['account_code']),
                'account_name' => trim($_POST['account_name']),
                'account_type' => $_POST['account_type'],
                'description' => trim($_POST['description'])
            ];

            if (empty($data['account_code']) || empty($data['account_name']) || empty($data['account_type'])) {
                setToastMessage('Account Code, Name, and Type are required.', 'error');
                redirect('finance/edit_account/' . $id);
                return;
            }

            if ($this->finance_model->updateAccount($data)) {
                $this->auth_service->audit('update_account', 'account', $id);
                setToastMessage('Account updated successfully.', 'success');
                redirect('finance/accounts');
            } else {
                setToastMessage('Failed to update account.', 'error');
                redirect('finance/edit_account/' . $id);
            }
        } else {
            redirect('finance/accounts');
        }
    }

    public function delete_account($id) {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/accounts');
        }
        if ($this->finance_model->deleteAccount($id)) {
            $this->auth_service->audit('delete_account', 'account', $id);
            setToastMessage('Account deleted successfully.', 'success');
        } else {
            setToastMessage('Failed to delete account. It may be in use.', 'error');
        }
        redirect('finance/accounts');
    }

    public function process_add_account() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/accounts');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'account_code' => trim($_POST['account_code']),
                'account_name' => trim($_POST['account_name']),
                'account_type' => $_POST['account_type'],
                'description' => trim($_POST['description'])
            ];

            if (empty($data['account_code']) || empty($data['account_name']) || empty($data['account_type'])) {
                setToastMessage('Account Code, Name, and Type are required.', 'error');
                redirect('finance/add_account');
                return;
            }

            if ($this->finance_model->addAccount($data)) {
                $this->auth_service->audit('add_account', 'account', $this->finance_model->db->lastInsertId());
                setToastMessage('Account added successfully.', 'success');
                redirect('finance/accounts');
            } else {
                setToastMessage('Failed to add account. The Account Code may already exist.', 'error');
                redirect('finance/add_account');
            }
        } else {
            redirect('finance/add_account');
        }
    }

    // == INVOICE METHODS ==

    public function invoices() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'Invoices';
        $invoices = $this->finance_model->getInvoices();
        require_once 'modules/finance/invoices_view.php';
    }

    public function create_invoice() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/invoices');
        }
        $page_title = 'Create New Invoice';
        $customers = $this->finance_model->getCustomers();
        require_once 'modules/finance/create_invoice_view.php';
    }

    public function process_create_invoice() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/invoices');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'customer_id' => $_POST['customer_id'],
                'issue_date' => $_POST['issue_date'],
                'due_date' => $_POST['due_date'],
                'invoice_number' => 'INV-' . time(),
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
                setToastMessage('Customer and at least one item are required.', 'error');
                redirect('finance/create_invoice');
                return;
            }

            $invoice_id = $this->finance_model->createInvoice($data);
            if ($invoice_id) {
                $this->auth_service->audit('create_invoice', 'invoice', $invoice_id);
                setToastMessage('Invoice created successfully.', 'success');
                redirect('finance/view_invoice/' . $invoice_id);
            } else {
                setToastMessage('Failed to create invoice.', 'error');
                redirect('finance/create_invoice');
            }
        } else {
            redirect('finance/create_invoice');
        }
    }

    public function cancel_invoice($id) {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/invoices');
        }
        if ($this->finance_model->cancelInvoice($id)) {
            $this->auth_service->audit('cancel_invoice', 'invoice', $id);
            setToastMessage('Invoice cancelled successfully.', 'success');
        } else {
            setToastMessage('Failed to cancel invoice. It may have already been paid.', 'error');
        }
        redirect('finance/invoices');
    }

    public function view_invoice($id) {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to view this page.', 'error');
            redirect('dashboard');
        }
        $page_title = 'View Invoice';
        $invoice = $this->finance_model->getInvoiceById($id);
        if (!$invoice) {
            redirect('finance/invoices');
            return;
        }
        $payments = $this->finance_model->getPaymentsForInvoice($id);
        require_once 'modules/finance/view_invoice_view.php';
    }

    // == PAYMENT METHODS ==

    public function process_add_payment() {
        if (!$this->auth_service->hasPermission('manage_finances')) {
            setToastMessage('You do not have permission to perform this action.', 'error');
            redirect('finance/invoices');
        }
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
                setToastMessage('Payment Date and a valid Amount are required.', 'error');
                redirect('finance/view_invoice/' . $invoice_id);
                return;
            }

            if ($this->finance_model->addPayment($data)) {
                $this->auth_service->audit('add_payment', 'invoice', $invoice_id, "Amount: " . $data['amount']);
                setToastMessage('Payment added successfully.', 'success');
            } else {
                setToastMessage('Failed to add payment.', 'error');
            }
            redirect('finance/view_invoice/' . $invoice_id);
        } else {
            redirect('finance/invoices');
        }
    }
}
