<?php

require_once 'includes/BaseModel.php';

class FinanceModel extends BaseModel {

    public function __construct() {
        parent::__construct();
        // Autoloading isn't set up for modules yet, so manual require is needed for now.
        // In a real app, a more robust autoloader would handle this.
    }

    public function getAccounts() {
        $this->db->query("SELECT * FROM accounts ORDER BY account_code ASC");
        return $this->db->resultSet();
    }

    public function getAccountById($id) {
        $this->db->query("SELECT * FROM accounts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addAccount($data) {
        $this->db->query("INSERT INTO accounts (account_code, account_name, account_type, description) VALUES (:account_code, :account_name, :account_type, :description)");
        $this->db->bind(':account_code', $data['account_code']);
        $this->db->bind(':account_name', $data['account_name']);
        $this->db->bind(':account_type', $data['account_type']);
        $this->db->bind(':description', $data['description']);

        return $this->db->execute();
    }

    public function updateAccount($data) {
        $this->db->query("UPDATE accounts SET account_code = :account_code, account_name = :account_name, account_type = :account_type, description = :description WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':account_code', $data['account_code']);
        $this->db->bind(':account_name', $data['account_name']);
        $this->db->bind(':account_type', $data['account_type']);
        $this->db->bind(':description', $data['description']);
        return $this->db->execute();
    }

    public function deleteAccount($id) {
        // Note: You might want to prevent deletion if the account has transactions.
        $this->db->query("DELETE FROM accounts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // == INVOICE METHODS ==

    public function getInvoices() {
        $this->db->query("
            SELECT i.*, c.name as customer_name
            FROM invoices i
            JOIN customers c ON i.customer_id = c.id
            ORDER BY i.issue_date DESC
        ");
        return $this->db->resultSet();
    }

    public function getInvoiceById($id) {
        $this->db->query("
            SELECT i.*, c.name as customer_name, c.address as customer_address, c.email as customer_email
            FROM invoices i
            JOIN customers c ON i.customer_id = c.id
            WHERE i.id = :id
        ");
        $this->db->bind(':id', $id);
        $invoice = $this->db->single();

        if ($invoice) {
            $this->db->query("SELECT * FROM invoice_items WHERE invoice_id = :id");
            $this->db->bind(':id', $id);
            $invoice['items'] = $this->db->resultSet();
        }

        return $invoice;
    }

    public function cancelInvoice($id) {
        $this->db->query("UPDATE invoices SET status = 'Cancelled' WHERE id = :id AND status != 'Paid'");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function createInvoice($data) {
        // This would typically be a transaction
        $this->db->query("
            INSERT INTO invoices (invoice_number, customer_id, issue_date, due_date, total_amount, created_by_user_id)
            VALUES (:invoice_number, :customer_id, :issue_date, :due_date, :total_amount, :created_by_user_id)
        ");
        $this->db->bind(':invoice_number', $data['invoice_number']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':issue_date', $data['issue_date']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':created_by_user_id', $_SESSION['user_id']);

        if ($this->db->execute()) {
            $invoice_id = $this->db->lastInsertId();

            foreach ($data['items'] as $item) {
                $this->db->query("
                    INSERT INTO invoice_items (invoice_id, description, quantity, unit_price, total)
                    VALUES (:invoice_id, :description, :quantity, :unit_price, :total)
                ");
                $this->db->bind(':invoice_id', $invoice_id);
                $this->db->bind(':description', $item['description']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total', $item['quantity'] * $item['unit_price']);

                if (!$this->db->execute()) {
                    // In a real app, you would roll back the transaction here
                    return false;
                }
            }
            return $invoice_id;
        }
        return false;
    }

    // Helper to get customers for dropdowns
    public function getCustomers() {
        $this->db->query("SELECT id, name FROM customers ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // == PAYMENT METHODS ==

    public function getPaymentsForInvoice($invoice_id) {
        $this->db->query("SELECT * FROM payments WHERE invoice_id = :invoice_id ORDER BY payment_date DESC");
        $this->db->bind(':invoice_id', $invoice_id);
        return $this->db->resultSet();
    }

    public function addPayment($data) {
        // This should also be a transaction
        // 1. Add the payment record
        $this->db->query("
            INSERT INTO payments (invoice_id, payment_date, amount, payment_method, notes, received_by_user_id)
            VALUES (:invoice_id, :payment_date, :amount, :payment_method, :notes, :received_by_user_id)
        ");
        $this->db->bind(':invoice_id', $data['invoice_id']);
        $this->db->bind(':payment_date', $data['payment_date']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':received_by_user_id', $_SESSION['user_id']);

        if (!$this->db->execute()) {
            return false;
        }

        // 2. Update the invoice's paid_amount
        $this->db->query("
            UPDATE invoices
            SET paid_amount = paid_amount + :amount
            WHERE id = :invoice_id
        ");
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':invoice_id', $data['invoice_id']);

        if (!$this->db->execute()) {
            // Rollback would be needed here
            return false;
        }

        // 3. (Optional) Update invoice status if fully paid
        $invoice = $this->getInvoiceById($data['invoice_id']);
        if ($invoice['paid_amount'] >= $invoice['total_amount']) {
            $this->db->query("UPDATE invoices SET status = 'Paid' WHERE id = :invoice_id");
            $this->db->bind(':invoice_id', $data['invoice_id']);
            $this->db->execute();
        }

        return true;
    }
}
