<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>
<center>
    <h2>Add Product</h2>
    <form action="/indomaret/process/products_process.php" method="post">
        <table cellpadding="10">
            <input type="hidden" name="action" value="add" />
            <tr>
                <td><label>Product Name:</label></td>
                <td><input type="text" name="name" required /></td>
            </tr>
            <tr>
                <td><label>Price:</label></td>
                <td><input type="number" name="price" step="0.01" required /></td>
            </tr>
            <tr>
                <td><label>Stocks:</label></td>
                <td><input type="number" name="stocks" step="1" required /></td>
            </tr>
            <tr>
    <td><label>Voucher:</label></td>
    <td>
        <input list="voucher" name="voucher_id" required>
        <datalist id="voucher">
            <?php
            $query = "SELECT * FROM voucher";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . htmlspecialchars($row['id']) . "'>" 
                    . htmlspecialchars($row['code']) . " - " 
                    . htmlspecialchars($row['discount']) . "%</option>";
            }
            ?>
        </datalist>
    </td>
</tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" style="float:right">Save</button>
                </td>
            </tr>
        </table>
    </form>
</center>
<?php include ROOTPATH . "/includes/footer.php"; ?>