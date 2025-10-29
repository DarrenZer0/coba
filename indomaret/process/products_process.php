<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $stocks = isset($_POST['stocks']) ? intval($_POST['stocks']) : 0;
    $voucher_id = isset($_POST['voucher_id']) && $_POST['voucher_id'] !== '' ? intval($_POST['voucher_id']) : null;

    if ($action == 'add') {
        $stmt = $conn->prepare("INSERT INTO products (name, price, stocks, voucher_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdii", $name, $price, $stocks, $voucher_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'edit') {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, stocks=?, voucher_id=? WHERE id=?");
        $stmt->bind_param("sdiii", $name, $price, $stocks, $voucher_id, $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'delete') {
    $stmt = $conn->prepare("DELETE FROM transactions_detail WHERE product_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}


    header("Location: ../pages/products/list.php");
    exit;
}