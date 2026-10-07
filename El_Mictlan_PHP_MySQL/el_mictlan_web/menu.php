<?php
require_once "config.php";

$categorias = ['Bebidas calientes','Comida','Bebidas frías'];
$categoria = $_GET['categoria'] ?? 'Todas';

if ($categoria !== 'Todas' && in_array($categoria, $categorias, true)) {
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE disponible=1 AND temporada=0 AND es_reyes=0 AND categoria=? ORDER BY id");
    $stmt->execute([$categoria]);
} else {
    $stmt = $pdo->query("SELECT * FROM productos WHERE disponible=1 AND temporada=0 AND es_reyes=0 ORDER BY FIELD(categoria,'Bebidas calientes','Comida','Bebidas frías'), id");
}
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menú | El Mictlán</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="page-hero">
    <p>EL MICTLÁN</p>
    <h1>Nuestro menú</h1>
    <span>Tradición mexicana en cada platillo y bebida.</span>
</section>

<section class="menu-page section">
    <a href="temporada.php" class="banner-temporada">
        <span>🕯️</span>
        <p>¿Buscas algo especial? Prueba nuestro <strong>menú de Día de Muertos</strong>, con datos curiosos de cada platillo.</p>
        <span class="banner-flecha">Ver menú ›</span>
    </a>

    <div class="filter-bar">
        <a class="<?php echo $categoria==='Todas'?'selected':''; ?>" href="menu.php">Todo</a>
        <?php foreach ($categorias as $cat): ?>
            <a class="<?php echo $categoria===$cat?'selected':''; ?>" href="menu.php?categoria=<?php echo urlencode($cat); ?>">
                <?php echo htmlspecialchars($cat); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="products-grid">
        <?php foreach ($productos as $producto): ?>
            <article class="product-card">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <span><?php echo htmlspecialchars($producto['categoria']); ?></span>
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
</section>

<div id="toast" class="toast">Producto agregado al carrito</div>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>