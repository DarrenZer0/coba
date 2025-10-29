<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM cashiers");
?>

<center>
    <h2>List cashiers</h2>
    <a href="add.php">Add cashiers</a><br><br>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Name cashiers</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= urlencode($row['id']) ?>">Edit</a>
                    </td>
                    <td>
                        <?php
                        $query_cek = mysqli_query(
                            $conn,
                            "SELECT sales.cashier_id 
     FROM cashiers 
     JOIN sales ON cashiers.id = sales.cashier_id 
     WHERE cashiers.id = " . intval($row['id'])
                        )
                        ;

                        if (mysqli_num_rows($query_cek) > 0) {
                            echo "<button disabled>Delete</button>";
                        } else {
                            ?>
                            <form action="/indomaret/process/cashiers_process.php" method="post"
                                onsubmit="return confirm('Are you sure you want to delete?')">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit">Delete</button>
                            </form>
                        <?php } ?>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</center>

<?php include ROOTPATH . "/includes/footer.php"; ?>