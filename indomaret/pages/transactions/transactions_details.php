<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$sale_id = isset($_GET['sale_id']) ? intval($_GET['sale_id']) : 0;
if ($sale_id <= 0) {
    echo "<center><h3>Invalid Transaction ID</h3></center>";
    include "../../includes/footer.php";
    exit;
}

$header_query = "
    SELECT s.id AS sale_id, s.created_at, s.status,
           c.name AS cashier_name, 
           v.code AS voucher_code
    FROM sales s
    LEFT JOIN cashiers c ON s.cashier_id = c.id
    LEFT JOIN voucher v ON s.voucher_id = v.id
    WHERE s.id = $sale_id
";
$header_result = mysqli_query($conn, $header_query);
$header = mysqli_fetch_assoc($header_result);

$query = "
    SELECT td.id, p.name AS product_name, td.quantity, td.price, td.subtotal
    FROM transactions_detail td
    JOIN products p ON td.product_id = p.id
    WHERE td.sale_id = $sale_id
    ORDER BY td.id ASC
";
$result = mysqli_query($conn, $query);

$total_query = "SELECT SUM(subtotal) AS total FROM transactions_detail WHERE sale_id = $sale_id";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'] ?? 0;
?>

<style>
del {
    color: red;
}
</style>

<center>
    <h2>Transaction Details</h2>

    <datalist id="products">
        <?php
        $query_product = mysqli_query($conn, "SELECT * FROM products");
        while ($product = mysqli_fetch_assoc($query_product)) { ?>
        <option value="<?= $product['name'] ?>">
            <?php } ?>
    </datalist>
    <input type="text" list="products" name="product_id" placeholder="Search Products..." autocomplete="off">

    <div style="margin-top:10px;">
        <?php if ($header['status'] !== 'paid') { ?>
        <button id="paymentButton" type="button">Payment</button>
        <?php } ?>
        <?php if ($header['status'] === 'paid') { ?>
        <span id="paymentStatus" style="margin-left:12px; font-weight:bold; color:green;">PAID</span>
        <?php } else { ?>
        <span id="paymentStatus" style="margin-left:12px; font-weight:bold; color:green;"></span>
        <?php } ?>
    </div>

    <a href="list.php">
        <-- Back to Sales</a><br><br>

            <table border="1" cellpadding="10" cellspacing="0" width="80%">
                <tr>
                    <td><?= htmlspecialchars($header['created_at']) ?></td>
                    <td align="right"><?= $header['sale_id'] ?> /
                        <?= htmlspecialchars($header['cashier_name'] ?? '-') ?></td>
                </tr>
            </table>
            <br>

            <table border="1" cellpadding="10" cellspacing="0" width="80%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
            $no = 1;
            $total_discounted = 0;
            while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= number_format($row['quantity'], 0, ',', '.') ?></td>

                        <?php
                    $display_price = number_format($row['price'], 0, ',', '.');
                    $harga_diskon = null;
                    $discounted_subtotal = $row['subtotal'];
                    if (!empty($header['voucher_code'])) {
                        $voucher_id = $header['voucher_code'];
                        $diskon = mysqli_query($conn, "SELECT discount, max_discount FROM voucher WHERE code = '" . mysqli_real_escape_string($conn, $voucher_id) . "'");
                        if (mysqli_num_rows($diskon) > 0) {
                            $diskon = mysqli_fetch_assoc($diskon);
                            $harga_diskon = $row['price'] - ($row['price'] * $diskon['discount'] / 100);
                            if ($diskon['max_discount'] > 0 && ($row['price'] * $diskon['discount'] / 100) > $diskon['max_discount']) {
                                $harga_diskon = $row['price'] - $diskon['max_discount'];
                            }
                            $discounted_subtotal = $harga_diskon * $row['quantity'];
                        }
                    }
                    $total_discounted += $discounted_subtotal;
                    ?>

                        <td>
                            <?php if ($harga_diskon !== null) { ?>
                            <del><?= $display_price ?></del><br>
                            <?= number_format($harga_diskon, 0, ',', '.') ?>
                            <?php } else { ?>
                            <?= $display_price ?>
                            <?php } ?>
                        </td>
                        <td><?= number_format($discounted_subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td colspan="4" align="right"><strong>Total</strong></td>
                        <td><strong><?= number_format($total_discounted, 0, ',', '.') ?></strong></td>
                    </tr>
                </tbody>
            </table>
</center>

<?php include "../../includes/footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('paymentButton');
    var status = document.getElementById('paymentStatus');
    if (!btn || !status) return;

    btn.addEventListener('click', function() {
        if (!confirm('Are you sure you want to mark this transaction as paid?')) return;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText === 'success') {
                alert('Transaction has been marked as paid.');
                status.textContent = 'PAID';
                btn.style.display = 'none';
            } else {
                alert('Failed to update payment status. Please try again.');
            }
        };
        xhr.send('action=mark_paid&sale_id=<?= $sale_id ?>');
    });
});
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark_paid') {
    $sale_id = intval($_POST['sale_id']);
    if ($sale_id > 0) {
        $update = mysqli_query($conn, "UPDATE sales SET status='paid' WHERE id=$sale_id");
        echo $update ? 'success' : 'error';    }
    exit;
}
?>