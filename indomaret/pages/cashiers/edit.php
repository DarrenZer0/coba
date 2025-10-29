<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cashier = null;

if ($id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM cashiers WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $cashier = mysqli_fetch_assoc($result);
    }
}

if (!$cashier) {
    echo "<p>Cashier not found.</p>";
    include ROOTPATH . "/includes/footer.php";
    exit;
}
?>

<center>
    <h2>Edit Cashiers</h2>
    <form action="/indomaret/process/cashiers_process.php" method="post">
        <table cellpadding="10">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($cashier['id']); ?>" />
            <tr>
                <td><label>Cashiers Name:</label></td>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($cashier['name']); ?>" required />
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" style="float:right">Update</button>
                </td>
            </tr>
        </table>
    </form>

</center>
    </center>
<?php include ROOTPATH . "/includes/footer.php"; ?>