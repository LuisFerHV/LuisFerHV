<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$carritoCantidad = 0;
if (!empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $cantidad) {
        $carritoCantidad += (int)$cantidad;
    }
}
?>
<header class="site-header">
    <a class="brand" href="index.php">
        <img src="assets/logo-elmictlan.jpeg" alt="Logo El Mictlán">
        <span>El Mictlán</span>
    </a>

    <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">☰</button>

    <nav id="mainNav">
        <a class="active" href="index.php">Inicio</a>
        <a href="menu.php">Menú</a>
        <a href="nosotros.php">Nuestra historia</a>
        <a href="ubicacion.php">Ubicación</a>
        <a href="contacto.php">Contacto</a>
        <a class="cart-link" href="carrito.php">🛒 <span id="cartCount"><?php echo $carritoCantidad; ?></span></a>
        <button class="search-btn" id="searchBtn" type="button" aria-label="Buscar">⌕</button>
    </nav>
</header>
