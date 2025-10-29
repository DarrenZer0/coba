<?php
define ('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/coba/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($action == 'add') {
        $stmt = $conn->prepare("INSERT INTO cashiers (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'edit') {
        $stmt = $conn->prepare("UPDATE cashiers SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM cashiers WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../pages/cashiers/list.php");
    exit;
}