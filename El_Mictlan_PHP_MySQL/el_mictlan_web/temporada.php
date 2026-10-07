<?php
require_once "config.php";
$stmt = $pdo->query(
    "SELECT * FROM productos WHERE disponible=1 AND (temporada=1 OR es_reyes=1)
     ORDER BY es_reyes, FIELD(categoria,'Bebidas calientes','Comida','Bebidas frías'), id"
);
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menú de temporada | El Mictlán</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="page-hero small ofrenda-hero">
    <div class="papel-picado" aria-hidden="true">
        <?php for ($i = 0; $i < 14; $i++): ?><span></span><?php endfor; ?>
    </div>

    <p class="tema-muertos">EL MICTLÁN · TEMPORADA</p>
    <h1 class="tema-muertos">Menú de Día de Muertos</h1>
    <span class="tema-muertos">Sabores que honran a quienes ya no están. Pasa el mouse (o toca) cada tarjeta.</span>

    <p class="tema-reyes">EL MICTLÁN · TEMPORADA</p>
    <h1 class="tema-reyes">Menú de Día de Reyes</h1>
    <span class="tema-reyes">7 roscas para compartir el 6 de enero. Pasa el mouse (o toca) cada tarjeta.</span>
</section>

<section class="ofrenda section">

    <div class="cambio-temporada">
        <button type="button" id="cambioTemporadaBtn" class="cambio-temporada-btn">
            <span class="tema-muertos">👑 Cambiar a Día de Reyes</span>
            <span class="tema-reyes">🕯️ Volver a Día de Muertos</span>
        </button>
    </div>

    <div class="velas-fila" aria-hidden="true">
        <?php for ($i = 0; $i < 7; $i++): ?><span class="vela"></span><?php endfor; ?>
    </div>

    <div class="ofrenda-grid">
        <?php foreach ($productos as $producto):
            $esReyes = (bool)$producto['es_reyes'];
            $claseTema = $esReyes ? 'tema-reyes' : 'tema-muertos';
            $icono = $esReyes ? '👑' : '🌼';
        ?>
            <div class="ofrenda-card <?php echo $claseTema; ?>" tabindex="0" role="button"
                 aria-label="Voltear tarjeta de <?php echo htmlspecialchars($producto['nombre']); ?> para ver un dato curioso">
                <div class="ofrenda-inner">

                    <div class="ofrenda-front">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            <span><?php echo htmlspecialchars($producto['categoria']); ?></span>
                        </div>
                        <div class="product-body">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <div class="product-bottom">
                                <strong>$<?php echo number_format($producto['precio'],2); ?></strong>
                                <button type="button" class="add-cart" data-id="<?php echo $producto['id']; ?>">Agregar</button>
                            </div>
                        </div>
                        <span class="ofrenda-tip">🖐 Toca para el dato curioso</span>
                    </div>

                    <div class="ofrenda-back">
                        <span class="dato-icono"><?php echo $icono; ?></span>
                        <p class="dato-titulo">¿Sabías qué...?</p>
                        <p><?php echo htmlspecialchars($producto['dato_curioso']); ?></p>
                        <div class="ofrenda-back-compra">
                            <strong>$<?php echo number_format($producto['precio'],2); ?></strong>
                            <button type="button" class="add-cart" data-id="<?php echo $producto['id']; ?>">Agregar</button>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="center-btn"><a class="btn light" href="menu.php">Ver menú completo</a></div>
</section>

<div id="toast" class="toast">Producto agregado al carrito</div>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>