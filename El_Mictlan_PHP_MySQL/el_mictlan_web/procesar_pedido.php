<?php
require_once "config.php";
if (session_status() === PHP_SESSION_NONE) session_start();

$carrito = $_SESSION['carrito'] ?? [];
if (!$carrito) {
    header("Location: carrito.php");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$notas = trim($_POST['notas'] ?? '');
$metodoPago = trim($_POST['metodo_pago'] ?? '');
$referenciaPago = trim($_POST['referencia_pago'] ?? '');

if ($nombre === '' || $telefono === '' || $direccion === '') {
    die("Faltan datos obligatorios. Regresa al formulario.");
}
if (!in_array($metodoPago, ['Tarjeta', 'PayPal'], true)) {
    die("Elige una forma de pago antes de confirmar.");
}

try {
    $pdo->beginTransaction();

    $ids = array_keys($carrito);
    $placeholders = implode(',', array_fill(0,count($ids),'?'));
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders) AND disponible=1");
    $stmt->execute($ids);
    $productos = $stmt->fetchAll();

    $total = 0;
    foreach ($productos as $p) {
        $total += $p['precio'] * ($carrito[$p['id']] ?? 0);
    }

    $numero = 'MICT-' . date('YmdHis') . '-' . random_int(100,999);

    $stmt = $pdo->prepare(
        "INSERT INTO pedidos (numero_confirmacion,nombre_cliente,telefono,direccion,notas,total,metodo_pago,referencia_pago)
         VALUES (?,?,?,?,?,?,?,?)"
    );
    $stmt->execute([$numero,$nombre,$telefono,$direccion,$notas,$total,$metodoPago,$referenciaPago ?: null]);
    $pedidoId = $pdo->lastInsertId();

    $detalle = $pdo->prepare(
        "INSERT INTO detalle_pedido
         (pedido_id,producto_id,nombre_producto,cantidad,precio_unitario,subtotal)
         VALUES (?,?,?,?,?,?)"
    );

    foreach ($productos as $p) {
        $cantidad = (int)$carrito[$p['id']];
        $subtotal = $p['precio'] * $cantidad;
        $detalle->execute([$pedidoId,$p['id'],$p['nombre'],$cantidad,$p['precio'],$subtotal]);
    }

    $pdo->commit();
    $_SESSION['carrito'] = [];

} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("No se pudo guardar el pedido: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pedido confirmado | El Mictlán</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="confirmation section">
    <div class="confirmation-card">
        <div class="check">✓</div>
        <p class="eyebrow">¡PEDIDO RECIBIDO!</p>
        <h1>Gracias, <?php echo htmlspecialchars($nombre); ?></h1>
        <p>Tu pedido fue guardado correctamente en nuestra base de datos.</p>
        <div class="confirmation-number">
            <span>Número de confirmación</span>
            <strong><?php echo htmlspecialchars($numero); ?></strong>
        </div>
        <p class="total-confirm">Total: <strong>$<?php echo number_format($total,2); ?></strong></p>
        <p class="metodo-confirm">Pagado con: <strong><?php echo htmlspecialchars($metodoPago); ?></strong></p>
        <a class="btn" href="index.php">Volver al inicio</a>
    </div>
</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>