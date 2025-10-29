<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}
?>
<center>
    <h2>Edit Product</h2>
    <?php if ($product) { ?>
    <form action="/indomaret/process/products_process.php" method="post">   
        <table cellpadding="10">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>" />
            <tr>
                <td><label>Product Name:</label></td>
                <td><input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required /></td>
            </tr>
            <tr>
                <td><label>Price:</label></td>
                <td><input type="number" name="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required /></td>
            </tr>
            <tr>
                <td><label>Stocks:</label></td>
                <td><input type="number" name="stocks" step="1" value="<?= htmlspecialchars($product['stocks']) ?>" required /></td>
            </tr>
            <tr>
                <td><label>Voucher:</label></td>
                <td>
                    <input list="voucher" name="voucher_id" value="<?= htmlspecialchars($product['voucher_id']) ?>" required>
                    <datalist id="voucher">
                        <?php
                        $query = "SELECT * FROM voucher";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($row['id'] == $product['voucher_id']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($row['id']) . "' " . $selected . ">" 
                                . htmlspecialchars($row['code']) . " - " 
                                . htmlspecialchars($row['discount']) . "%</option>";
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
                <td></td>
                <td>
                    <button type="submit" style="float:right">Update</button>
                </td>
            </tr>
        </table>
    </form>
    <?php } else { ?>
        <p>Product not found.</p>
    <?php } ?>
</center>
<?php include ROOTPATH . "/includes/footer.php"; ?>