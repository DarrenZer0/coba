<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM products");
?>

<center>
    <h2>List Products</h2>
    <a href="add.php">Add Products</a><br><br>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Name Products</th>
                <th>Price</th>
                <th>Voucher ID</th>
                <th>Stocks</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?= $no++?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <?php
                $voucher_id = $row['voucher_id'];
                $diskon = mysqli_query($conn, "SELECT discount, max_discount FROM voucher WHERE id = '$voucher_id'");
                if(mysqli_num_rows($diskon) > 0){
                    $diskon = mysqli_fetch_assoc($diskon);
                    $harga_diskon = $row['price'] - ($row['price'] * $diskon['discount'] / 100);
                    if($diskon['max_discount'] > 0 && ($row['price'] * $diskon['discount'] / 100) > $diskon['max_discount']){
                        $harga_diskon = $row['price'] - $diskon['max_discount'];
                    }             
                ?>
                <td><del><?= number_format($row['price'], 0, ',', '.') ?></del><br>
                    <?= number_format($harga_diskon, 0, ',', '.') ?></td>
                <?php
                }else{
                ?>
                <td><?= number_format($row['price'], 0, ',', '.') ?></td>
                <?php
                }
                ?>


                <td><?= htmlspecialchars($row['voucher_id']) ?></td>
                <td><?= number_format($row['stocks'], 0, ',', '.') ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                </td>
                <td>
                    <form action="/indomaret/process/products_process.php" method="post"
                        onsubmit="return confirm('Are you sure you want to delete?')">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</center>

<?php include "../../includes/footer.php"; ?>