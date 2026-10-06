<?php
require_once "config.php";
header("Content-Type: application/json; charset=utf-8");

try {
    $materias = $pdo->query("SELECT id, nombre FROM materias ORDER BY orden, id")->fetchAll();

    $contenidoStmt = $pdo->prepare(
        "SELECT id, texto, archivo, archivo_nombre, editado, fecha
         FROM materia_contenido WHERE materia_id=? ORDER BY fecha DESC"
    );

    $resultado = [];
    foreach ($materias as $materia) {
        $contenidoStmt->execute([$materia['id']]);
        $resultado[] = [
            'id' => (int)$materia['id'],
            'nombre' => $materia['nombre'],
            'contenido' => array_map(function ($c) {
                return [
                    'id' => (int)$c['id'],
                    'texto' => $c['texto'],
                    'archivo' => $c['archivo'],
                    'archivo_nombre' => $c['archivo_nombre'],
                    'editado' => (bool)$c['editado'],
                    'fecha' => date('d/m/Y H:i', strtotime($c['fecha']))
                ];
            }, $contenidoStmt->fetchAll())
        ];
    }

    echo json_encode(['ok' => true, 'materias' => $resultado]);

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
}