<?php
require_once "config.php";
if (session_status() === PHP_SESSION_NONE) session_start();

$carrito = $_SESSION['carrito'] ?? [];
if (!$carrito) {
    header("Location: carrito.php");
    exit;
}

$ids = array_keys($carrito);
$placeholders = implode(',', array_fill(0,count($ids),'?'));
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
$stmt->execute($ids);
$productos = $stmt->fetchAll();

$total = 0;
foreach ($productos as $p) $total += $p['precio'] * ($carrito[$p['id']] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmar pedido | El Mictlán</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="page-hero small">
    <p>EL MICTLÁN</p>
    <h1>Confirmar pedido</h1>
</section>

<section class="checkout section">
    <div class="checkout-info">
        <h2>Datos para tu pedido</h2>
        <p>Completa los datos para guardar tu pedido y generar un número de confirmación.</p>
        <form action="procesar_pedido.php" method="post" class="checkout-form">
            <label>Nombre completo
                <input type="text" name="nombre" required maxlength="120">
            </label>
            <label>Teléfono
                <input type="tel" name="telefono" required maxlength="30">
            </label>
            <label>Dirección de entrega
                <input type="text" name="direccion" required maxlength="255"
                       placeholder="Calle, número, colonia, Ecatepec">
            </label>
            <label>Notas del pedido
                <textarea name="notas" rows="4" maxlength="1000" placeholder="Alguna indicación especial..."></textarea>
            </label>
            <button class="btn" type="submit">Confirmar pedido · $<?php echo number_format($total,2); ?></button>
        </form>
    </div>

    <aside class="checkout-summary">
        <h2>Tu pedido</h2>
        <?php foreach ($productos as $p): ?>
            <div class="summary-product">
                <span><?php echo (int)$carrito[$p['id']]; ?> × <?php echo htmlspecialchars($p['nombre']); ?></span>
                <strong>$<?php echo number_format($p['precio'] * $carrito[$p['id']],2); ?></strong>
            </div>
        <?php endforeach; ?>
        <hr>
        <div class="total-line"><span>Total</span><strong>$<?php echo number_format($total,2); ?></strong></div>
    </aside>
</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>
