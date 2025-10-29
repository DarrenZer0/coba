<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>

<center>
    <h2>Add Cashiers</h2>
    <form action="/indomaret/process/cashiers_process.php" method="post">
        <table cellpadding="10">
            <input type="hidden" name="action" value="add" />
            <tr>
                <td><label>Cashiers Name:</label></td>
                <td><input type="text" name="name" required /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" style="float:right">Simpan</button>
                </td>
            </tr>
        </table>
    </form>

</center>
<?php include ROOTPATH . "/includes/footer.php"; ?>