<?php
require_once "config.php";
if (session_status() === PHP_SESSION_NONE) session_start();

$carrito = $_SESSION['carrito'] ?? [];
$items = [];
$total = 0;

if ($carrito) {
    $ids = array_keys($carrito);
    $placeholders = implode(',', array_fill(0,count($ids),'?'));
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $productos = $stmt->fetchAll();

    foreach ($productos as $p) {
        $cantidad = (int)($carrito[$p['id']] ?? 0);
        $subtotal = $p['precio'] * $cantidad;
        $total += $subtotal;
        $items[] = ['p'=>$p,'cantidad'=>$cantidad,'subtotal'=>$subtotal];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrito | El Mictlán</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="page-hero small">
    <p>EL MICTLÁN</p>
    <h1>Tu carrito</h1>
</section>

<section class="cart-section section">
<?php if (!$items): ?>
    <div class="empty-cart">
        <div>🛒</div>
        <h2>Tu carrito está vacío</h2>
        <p>Agrega alguna bebida o comida tradicional para comenzar.</p>
        <a class="btn" href="menu.php">Ir al menú</a>
    </div>
<?php else: ?>
    <div class="cart-layout">
        <div class="cart-items">
        <?php foreach ($items as $item): $p=$item['p']; ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($p['imagen']); ?>" alt="">
                <div class="cart-item-info">
                    <h3><?php echo htmlspecialchars($p['nombre']); ?></h3>
                    <p>$<?php echo number_format($p['precio'],2); ?> c/u</p>
                    <form action="actualizar_carrito.php" method="post" class="quantity-form">
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                        <input type="number" min="0" name="cantidad" value="<?php echo $item['cantidad']; ?>">
                        <button type="submit">Actualizar</button>
                    </form>
                </div>
                <strong>$<?php echo number_format($item['subtotal'],2); ?></strong>
            </div>
        <?php endforeach; ?>
        </div>

        <aside class="order-box">
            <h2>Resumen</h2>
            <div class="total-line"><span>Total</span><strong>$<?php echo number_format($total,2); ?></strong></div>
            <p class="mini">El pedido se guardará en la base de datos al confirmar.</p>
            <a class="btn full" href="confirmar.php">Continuar pedido</a>
        </aside>
    </div>
<?php endif; ?>
</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>
