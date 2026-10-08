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

        <form action="procesar_pedido.php" method="post" class="checkout-form" id="formPedido">
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

            <input type="hidden" name="metodo_pago" id="metodoPago" value="">
            <input type="hidden" name="referencia_pago" id="referenciaPago" value="">

            <div class="pago-seccion">
                <h3>¿Cómo quieres pagar?</h3>

                <div class="pago-tabs" role="tablist">
                    <button type="button" class="pago-tab activa" id="tabTarjeta" data-metodo="tarjeta">💳 Tarjeta</button>
                    <button type="button" class="pago-tab" id="tabPaypal" data-metodo="paypal">🅿️ PayPal</button>
                </div>

                <div class="pago-panel" id="panelTarjeta">
                    <div class="tarjeta-preview" id="tarjetaPreview">
                        <div class="tarjeta-chip"></div>
                        <div class="tarjeta-marca" id="tarjetaMarca"></div>
                        <div class="tarjeta-numero" id="tarjetaNumeroVista">•••• •••• •••• ••••</div>
                        <div class="tarjeta-fila-inferior">
                            <div>
                                <span class="tarjeta-label">Titular</span>
                                <div id="tarjetaNombreVista">NOMBRE APELLIDO</div>
                            </div>
                            <div>
                                <span class="tarjeta-label">Vence</span>
                                <div id="tarjetaVenceVista">MM/AA</div>
                            </div>
                        </div>
                    </div>

                    <label>Nombre en la tarjeta
                        <input type="text" id="tarjetaNombre" autocomplete="cc-name" placeholder="Como aparece en tu tarjeta">
                    </label>
                    <label>Número de tarjeta
                        <input type="text" id="tarjetaNumero" inputmode="numeric" autocomplete="cc-number"
                               maxlength="19" placeholder="0000 0000 0000 0000">
                    </label>
                    <div class="pago-fila-doble">
                        <label>Vencimiento
                            <input type="text" id="tarjetaVence" autocomplete="cc-exp" maxlength="5" placeholder="MM/AA">
                        </label>
                        <label>CVC
                            <input type="text" id="tarjetaCvc" inputmode="numeric" autocomplete="cc-csc" maxlength="4" placeholder="123">
                        </label>
                    </div>

                    <button type="button" class="btn full" id="btnPagarTarjeta">Pagar $<?php echo number_format($total,2); ?> con tarjeta</button>
                    <p class="pago-aviso"></p>
                </div>

                <div class="pago-panel" id="panelPaypal" hidden>
                    <div id="paypalBotones"></div>
                    <p class="pago-aviso" id="paypalAviso">
                       
                    </p>
                </div>
            </div>
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

<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=MXN"></script>
<script src="script.js"></script>
<script>
    window.TOTAL_PEDIDO = <?php echo json_encode((float)$total); ?>;
</script>
</body>
</html>