<?php
require_once "config.php";
header("Content-Type: application/json; charset=utf-8");

$id = (int)($_POST['id'] ?? 0);
$texto = trim($_POST['texto'] ?? '');

if ($id <= 0 || $texto === '') {
    echo json_encode(['ok' => false, 'error' => 'Escribe un contenido antes de guardar.']);
    exit;
}
if (mb_strlen($texto) > 1000) {
    echo json_encode(['ok' => false, 'error' => 'El contenido es muy largo.']);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM materia_contenido WHERE id=?");
$stmt->execute([$id]);
if (!$stmt->fetch()) {
    echo json_encode(['ok' => false, 'error' => 'Ese contenido ya no existe.']);
    exit;
}

$stmt = $pdo->prepare("UPDATE materia_contenido SET texto=?, editado=1 WHERE id=?");
$stmt->execute([$texto, $id]);

echo json_encode(['ok' => true, 'texto' => htmlspecialchars($texto)]);
