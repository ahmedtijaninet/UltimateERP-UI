<?php require_once '../../includes/views/header.php'; ?>

<div class="container">
    <h2>Create New Sales Order</h2>

    <form action="<?php echo SITE_URL; ?>/sales/process_create_so" method="post" id="so-form">
        <div class="so-header">
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
                <label for="order_date">Order Date</label>
                <input type="date" name="order_date" id="order_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>

        <h3>Items to Order</h3>
        <div class="form-group">
            <label for="item_search">Add Item</label>
            <select id="item_search">
                <option value="">-- Search for an item --</option>
                <?php foreach ($items as $item): ?>
                    <option value="<?php echo $item['id']; ?>" data-price="<?php echo $item['unit_price']; ?>" data-description="<?php echo htmlspecialchars($item['description']); ?>">
                        <?php echo htmlspecialchars($item['item_code']) . ' - ' . htmlspecialchars($item['description']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <table class="table" id="so-items-table">
            <thead>
                <tr>
                    <th>Item</th>
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
                    <td colspan="4" style="text-align: right; font-weight: bold;">Subtotal</td>
                    <td id="subtotal">$0.00</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <hr style="margin: 30px 0;">

        <button type="submit" class="btn">Create Sales Order</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSearch = document.getElementById('item_search');
    const itemsTableBody = document.querySelector('#so-items-table tbody');
    const subtotalCell = document.getElementById('subtotal');

    itemSearch.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (!selectedOption.value) return;

        const itemId = selectedOption.value;
        const itemPrice = selectedOption.getAttribute('data-price');
        const itemDescription = selectedOption.getAttribute('data-description');

        addRow({
            id: itemId,
            description: itemDescription,
            price: itemPrice
        });

        this.selectedIndex = 0; // Reset dropdown
    });

    function addRow(item) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="hidden" name="items[id][]" value="${item.id}">
                ${item.description.substring(0, 30)}...
            </td>
            <td><input type="text" name="items[description][]" class="item-input" value="${item.description}"></td>
            <td><input type="number" name="items[quantity][]" class="item-input quantity" value="1" min="1"></td>
            <td><input type="number" name="items[unit_price][]" class="item-input unit-price" step="0.01" value="${item.price}"></td>
            <td class="item-total">$${parseFloat(item.price).toFixed(2)}</td>
            <td><button type="button" class="remove-item-btn">&times;</button></td>
        `;
        itemsTableBody.appendChild(row);
        updateTotals();
    }

    function updateTotals() {
        let currentSubtotal = 0;
        document.querySelectorAll('#so-items-table tbody tr').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            const total = quantity * unitPrice;
            row.querySelector('.item-total').textContent = '$' + total.toFixed(2);
            currentSubtotal += total;
        });
        subtotalCell.textContent = '$' + currentSubtotal.toFixed(2);
    }

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
});
</script>

<style>
.so-header {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
</style>

<?php require_once '../../includes/views/footer.php'; ?>
