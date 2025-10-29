<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $cashier_name = $_POST['cashier_name'];
    $voucher_code = isset($_POST['voucher_code']) ? $_POST['voucher_code'] : '';

    $cashier = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM cashiers WHERE name='" . mysqli_real_escape_string($conn, $cashier_name) . "'"));
    $cashier_id = $cashier ? $cashier['id'] : null;

    $voucher_id = null;
    if ($voucher_code !== '') {
        $voucher = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM voucher WHERE code='" . mysqli_real_escape_string($conn, $voucher_code) . "'"));
        if ($voucher) {
            $voucher_id = $voucher['id'];
        }
    }

    $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM sales ORDER BY id DESC LIMIT 1"));
    if ($last) {
        $urutan = (int)$last['id'] + 1;
        $code = 'TRX' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    } else {
        $code = 'TRX0001';
    }

    date_default_timezone_set('Asia/Makassar');
    $created_at = date('Y-m-d H:i:s');
    $total = 0;

    $insert = mysqli_query($conn, "INSERT INTO sales (created_at, cashier_id, voucher_id) VALUES ('{$created_at}'," . ($cashier_id ? $cashier_id : 'NULL') . ", " . ($voucher_id ? $voucher_id : 'NULL') . ")");
    if ($insert) {
        $sale_id = mysqli_insert_id($conn);

        // Insert products into transactions_detail
        if (!empty($_POST['product_id']) && !empty($_POST['quantity'])) {
            $product_ids = $_POST['product_id'];
            $quantities = $_POST['quantity'];
            $grand_total = 0;
            foreach ($product_ids as $idx => $pid) {
                $qty = intval($quantities[$idx]);
                if ($qty > 0) {
                    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM products WHERE id=" . intval($pid)));
                    $price = $product ? floatval($product['price']) : 0;
                    $subtotal = $price * $qty;
                    $grand_total += $subtotal;
                    mysqli_query($conn, "INSERT INTO transactions_detail (sale_id, product_id, quantity, price, subtotal) VALUES ($sale_id, $pid, $qty, $price, $subtotal)");
                }
            }
        }

        header("Location: transactions_details.php?sale_id=" . $sale_id);
        exit;
    } else {
        echo '<p style="color:red">Failed to add transaction: ' . mysqli_error($conn) . '</p>';
    }
}
?>
<center>
    <h2>Add Transaction</h2>
    <form method="post" action="">
        <table cellpadding="10">
            <tr>
                <td><label for="cashier_name">Cashier:</label></td>
                <td>
                    <input type="text" name="cashier_name" list="cashierList" required autocomplete="off">
                    <datalist id="cashierList">
                        <?php
                        $q = mysqli_query($conn, "SELECT name FROM cashiers");
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo "<option value='" . htmlspecialchars($row['name']) . "'>";
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td><label for="voucher_code">Voucher (optional):</label></td>
                <td>
                    <input type="text" name="voucher_code" list="voucherList">
                    <datalist id="voucherList">
                        <?php
                        $q = mysqli_query($conn, "SELECT code FROM voucher");
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo "<option value='" . htmlspecialchars($row['code']) . "'>";
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Products:</strong></td>
            </tr>
                    <tr>
                        <td colspan="2">
                            <label for="productSearch">Search products:</label>
                            <input type="search" id="productSearch" placeholder="Type product name to filter" autocomplete="off" style="width:100%; padding:6px;">
                        </td>
                    </tr>
            <?php
            $products = mysqli_query($conn, "SELECT id, name, price, stocks FROM products");
            while ($p = mysqli_fetch_assoc($products)) {
            ?>
            <tr class="product-row" data-name="<?= htmlspecialchars(strtolower($p['name']), ENT_QUOTES) ?>">
                <td>
                    <button type="button" class="add-btn" data-id="<?= $p['id'] ?>" data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>" data-price="<?= $p['price'] ?>">Add</button>
                    <?= htmlspecialchars($p['name']) ?> <br>
                    <small>Price: <?= number_format($p['price'], 0, ',', '.') ?> | Stock: <?= number_format($p['stocks'], 0, ',', '.') ?></small>
                </td>
                <td>
                    <input type="hidden" name="product_id[]" value="<?= $p['id'] ?>">
                    <input type="number" name="quantity[]" min="0" max="<?= $p['stocks'] ?>" value="0">
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td></td>
                <td><button type="submit" name="submit">Add Transaction</button></td>
            </tr>
        </table>
    </form>
</center>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var search = document.getElementById('productSearch');
    if (search) {
        search.addEventListener('input', function() {
            var q = this.value.trim().toLowerCase();
            var rows = document.querySelectorAll('.product-row');
            rows.forEach(function(r) {
                var name = r.getAttribute('data-name') || '';
                if (q === '' || name.indexOf(q) !== -1) {
                    r.style.display = '';
                } else {
                    r.style.display = 'none';
                }
            });
        });
    }

    var addButtons = document.querySelectorAll('.add-btn');
    addButtons.forEach(function(btn) {x
        btn.addEventListener('click', function() {
            var row = btn.closest('.product-row');
            if (!row) return;
            var qtyInput = row.querySelector('input[type="number"][name="quantity[]"]');
            if (qtyInput) {
                qtyInput.value = Math.max(1, parseInt(qtyInput.value) || 1);
                qtyInput.focus();
            }
        });
    });
});
</script>

<?php include ROOTPATH . "/includes/footer.php"; ?>
