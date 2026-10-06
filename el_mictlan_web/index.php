<?php
require_once "config.php";
$stmt = $pdo->query("SELECT * FROM productos WHERE disponible=1 ORDER BY id LIMIT 5");
$destacados = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>El Mictlán | Cafetería mexicana</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="small-title">CAFETERÍA</div>
        <h1>El Mictlán</h1>
        <p>Sabores, cultura y tradición en cada taza</p>
        <a class="btn" href="menu.php">Conoce nuestro menú <span>›</span></a>
    </div>
</section>

<section class="essence section">
    <div class="essence-image">
        <img src="imn/cafe-pan.jpeg" alt="Café y pan tradicional">
    </div>
    <div class="essence-text">
        <h2>Nuestra esencia</h2>
        <p>
            En El Mictlán creemos que el café es más que una bebida:
            es un momento para compartir, disfrutar y mantener vivas
            nuestras raíces. Nuestra propuesta combina la tradición
            mexicana con el ambiente cálido de una cafetería.
        </p>
        <p class="signature">✿ Tradición que se disfruta</p>
    </div>
    <div class="features">
        <div><b>☕ Café de calidad</b><span>Sabores preparados para disfrutar cada taza.</span></div>
        <div><b>🥐 Pan tradicional</b><span>Antojitos y pan dulce con sabor mexicano.</span></div>
        <div><b>♡ Ambiente acogedor</b><span>Un espacio para compartir y sentirte como en casa.</span></div>
    </div>
</section>

<section class="menu-preview">
    <div class="section-title">
        <span>❧</span><h2>Nuestro menú</h2><span>❧</span>
    </div>

    <div class="category-grid">
        <a href="menu.php?categoria=Bebidas+calientes" class="category-card">
            <img src="imn/cafe-granos.jpeg" alt="Bebidas calientes">
            <div><span>☕</span><h3>Bebidas calientes</h3><p>Chocolate, champurrado, café, ponche y atole.</p></div>
        </a>
        <a href="menu.php?categoria=Comida" class="category-card">
            <img src="imn/cafe-pan.jpeg" alt="Comida">
            <div><span>🥐</span><h3>Comida</h3><p>Tamales, pan de elote, churros y especialidades.</p></div>
        </a>
        <a href="menu.php?categoria=Bebidas+frías" class="category-card">
            <img src="imn/horchata.webp" alt="Bebidas frías">
            <div><span>🥤</span><h3>Bebidas frías</h3><p>Horchata, pozol, raspados y frapes.</p></div>
        </a>
    </div>

    <div class="center-btn"><a class="btn light" href="menu.php">Ver todo el menú</a></div>
</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>
