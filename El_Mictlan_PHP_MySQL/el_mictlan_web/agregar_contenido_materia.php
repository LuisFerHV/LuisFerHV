<?php
require_once "config.php";
header("Content-Type: application/json; charset=utf-8");

$materiaId = (int)($_POST['materia_id'] ?? 0);
$texto = trim($_POST['texto'] ?? '');
$hayArchivo = isset($_FILES['archivo']) && $_FILES['archivo']['error'] !== UPLOAD_ERR_NO_FILE;

if ($materiaId <= 0) {
    echo json_encode(['ok' => false, 'error' => 'Falta indicar la materia.']);
    exit;
}
if ($texto === '' && !$hayArchivo) {
    echo json_encode(['ok' => false, 'error' => 'Escribe algo o adjunta un archivo.']);
    exit;
}
if (mb_strlen($texto) > 1000) {
    echo json_encode(['ok' => false, 'error' => 'El contenido es muy largo.']);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM materias WHERE id=?");
$stmt->execute([$materiaId]);
if (!$stmt->fetch()) {
    echo json_encode(['ok' => false, 'error' => 'Materia no encontrada.']);
    exit;
}

$rutaGuardada = null;
$nombreOriginal = null;

if ($hayArchivo) {
    $archivo = $_FILES['archivo'];

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['ok' => false, 'error' => 'No se pudo subir el archivo.']);
        exit;
    }

    $maxBytes = 8 * 1024 * 1024; // 8 MB
    if ($archivo['size'] > $maxBytes) {
        echo json_encode(['ok' => false, 'error' => 'El archivo pesa más de 8 MB.']);
        exit;
    }

    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $permitidas = ['pdf', 'doc', 'docx'];
    if (!in_array($extension, $permitidas, true)) {
        echo json_encode(['ok' => false, 'error' => 'Solo se aceptan archivos Word (.doc, .docx) o PDF.']);
        exit;
    }

    $carpeta = __DIR__ . '/materiales';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0755, true);
    }

    $nombreArchivo = 'materia' . $materiaId . '_' . date('YmdHis') . '_' . random_int(100, 999) . '.' . $extension;
    $rutaDestino = $carpeta . '/' . $nombreArchivo;

    if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        echo json_encode(['ok' => false, 'error' => 'No se pudo guardar el archivo en el servidor.']);
        exit;
    }

    $rutaGuardada = 'materiales/' . $nombreArchivo;
    $nombreOriginal = $archivo['name'];
}

$stmt = $pdo->prepare(
    "INSERT INTO materia_contenido (materia_id, texto, archivo, archivo_nombre) VALUES (?, ?, ?, ?)"
);
$stmt->execute([
    $materiaId,
    $texto !== '' ? $texto : null,
    $rutaGuardada,
    $nombreOriginal
]);

echo json_encode([
    'ok' => true,
    'contenido' => [
        'id' => (int)$pdo->lastInsertId(),
        'texto' => $texto !== '' ? htmlspecialchars($texto) : null,
        'archivo' => $rutaGuardada,
        'archivo_nombre' => $nombreOriginal ? htmlspecialchars($nombreOriginal) : null,
        'editado' => false,
        'fecha' => date('d/m/Y H:i')
    ]
]);