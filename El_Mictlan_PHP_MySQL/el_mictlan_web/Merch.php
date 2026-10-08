<?php
require_once "config.php";
$stmt = $pdo->query("SELECT * FROM productos WHERE disponible=1 AND categoria='Merch' ORDER BY id");
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Merch 3D | El Mictlán</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="page-hero merch-hero">
    <p>EL MICTLÁN · COLECCIÓN</p>
    <h1>Merch 3D</h1>
    <span>Figuras modeladas en Maya e impresas en 3D, inspiradas en nuestra cafetería.</span>
</section>

<section class="merch-page section">
    <div class="products-grid">
        <?php foreach ($productos as $producto): ?>
            <article class="product-card merch-card">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <span>🧊 Impresión 3D</span>
                </div>
                <div class="product-body">
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <div class="product-bottom">
                        <strong>$<?php echo number_format($producto['precio'],2); ?></strong>
                        <button class="add-cart" data-id="<?php echo $producto['id']; ?>">Agregar</button>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <p class="merch-nota">Piezas hechas por encargo: el tiempo de entrega puede variar según la fila de impresión. 🖨️</p>
</section>

<div id="toast" class="toast">Producto agregado al carrito</div>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>