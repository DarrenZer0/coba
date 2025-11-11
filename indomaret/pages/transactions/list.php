<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$query = "
    SELECT s.id AS sale_id, 
           c.name AS cashier_name, 
           v.code AS voucher_code,
           s.status
    FROM sales s
    LEFT JOIN cashiers c ON s.cashier_id = c.id
    LEFT JOIN voucher v ON s.voucher_id = v.id
    ORDER BY s.id DESC
";

$result = mysqli_query($conn, $query);
?>

<center>
    <h2>List Sales</h2>
    <a href="add.php">Add Sale</a><br><br>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Sale ID</th>
                <th>Cashier</th>
                <th>Voucher</th>
                <th>Status</th>
                <th colspan="4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['sale_id']) ?></td>
                <td><?= htmlspecialchars($row['cashier_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['voucher_code'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['status'] ?? '-') ?></td>
                <td>
                    <a href="transactions_details.php?sale_id=<?= $row['sale_id'] ?>">View Details</a>
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['sale_id'] ?>">Edit</a>
                </td>
                <td>
                    <form action="/indomaret/process/transactions_process.php" method="post"
                        onsubmit="return confirm('Are you sure you want to delete this sale?')">
                        <input type="hidden" name="id" value="<?= $row['sale_id'] ?>">
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