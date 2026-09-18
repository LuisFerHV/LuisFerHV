<?php
require_once "config.php";
if (session_status() === PHP_SESSION_NONE) session_start();

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['ok'=>false]);
    exit;
}
$stmt = $pdo->prepare("SELECT id FROM productos WHERE id=? AND disponible=1");
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    echo json_encode(['ok'=>false]);
    exit;
}
if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];
$_SESSION['carrito'][$id] = ($_SESSION['carrito'][$id] ?? 0) + 1;

$totalItems = array_sum($_SESSION['carrito']);
echo json_encode(['ok'=>true,'cantidad'=>$totalItems]);
