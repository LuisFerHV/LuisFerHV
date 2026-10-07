<?php
require_once "config.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: carrito.php");
    exit;
}

$accion = $_POST['accion'] ?? 'uno';

if ($accion === 'todo') {

    $_SESSION['carrito'] = [];
} else {

    $id = (int)($_POST['id'] ?? 0);
    if (isset($_SESSION['carrito'][$id])) {
        unset($_SESSION['carrito'][$id]);
    }
}

header("Location: carrito.php");
exit;