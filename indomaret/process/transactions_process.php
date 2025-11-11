<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$stmt = $conn->prepare("DELETE FROM transactions_detail WHERE sale_id=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

	$stmt = $conn->prepare("DELETE FROM sales WHERE id=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();

	header("Location: ../pages/transactions/list.php");
	exit;
}