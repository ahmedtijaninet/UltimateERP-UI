<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h2>Create New Invoice</h2>

    <form action="<?php echo SITE_URL; ?>/finance/process_create_invoice" method="post" id="invoice-form">
        <div class="invoice-header">
            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select name="customer_id" id="customer_id" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>"><?php echo htmlspecialchars($customer['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="issue_date">Issue Date</label>
                <input type="date" name="issue_date" id="issue_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>" required>
            </div>
        </div>

        <h3>Invoice Items</h3>
        <table class="table" id="invoice-items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- Item rows will be added here by JavaScript -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">Subtotal</td>
                    <td id="subtotal">$0.00</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <button type="button" id="add-item-btn" class="btn btn-secondary" style="margin-top: 10px;">Add Item</button>

        <hr style="margin: 30px 0;">

        <button type="submit" class="btn">Create Invoice</button>
        <a href="<?php echo SITE_URL; ?>/finance/invoices" style="margin-left: 10px;">Cancel</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addItemBtn = document.getElementById('add-item-btn');
    const itemsTableBody = document.querySelector('#invoice-items-table tbody');
    const subtotalCell = document.getElementById('subtotal');
    let itemIndex = 0;

    function addRow() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="items[description][]" class="item-input" required></td>
            <td><input type="number" name="items[quantity][]" class="item-input quantity" value="1" min="1"></td>
            <td><input type="number" name="items[unit_price][]" class="item-input unit-price" step="0.01" value="0.00"></td>
            <td class="item-total">$0.00</td>
            <td><button type="button" class="remove-item-btn">&times;</button></td>
        `;
        itemsTableBody.appendChild(row);
        itemIndex++;
    }

    function updateTotals() {
        let currentSubtotal = 0;
        document.querySelectorAll('#invoice-items-table tbody tr').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            const total = quantity * unitPrice;
            row.querySelector('.item-total').textContent = '$' + total.toFixed(2);
            currentSubtotal += total;
        });
        subtotalCell.textContent = '$' + currentSubtotal.toFixed(2);
    }

    addItemBtn.addEventListener('click', addRow);

    itemsTableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('tr').remove();
            updateTotals();
        }
    });

    itemsTableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-input')) {
            updateTotals();
        }
    });

    // Add one row to start with
    addRow();
});
</script>

<style>
.invoice-header {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
#invoice-items-table input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}
#invoice-items-table .item-total {
    font-weight: bold;
}
.remove-item-btn {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-weight: bold;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
