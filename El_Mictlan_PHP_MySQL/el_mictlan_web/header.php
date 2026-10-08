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
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<script>
    document.documentElement.setAttribute(
        "data-tema",
        localStorage.getItem("temaReyes") === "1" ? "reyes" : "muertos"
    );
</script>
<header class="site-header">
    <a class="brand" href="index.php">
        <img src="imn/logo-elmictlan.jpeg" alt="Logo El Mictlán">
        <span>El Mictlán</span>
    </a>

    <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="mainNav">☰</button>

    <nav id="mainNav">
        <a class="<?php echo $paginaActual === 'index.php' ? 'active' : ''; ?>" href="index.php">Inicio</a>
        <a class="<?php echo $paginaActual === 'menu.php' ? 'active' : ''; ?>" href="menu.php">Menú</a>
        <a class="<?php echo $paginaActual === 'temporada.php' ? 'active' : ''; ?>" href="temporada.php">🕯️ Temporada</a>
        <a class="<?php echo $paginaActual === 'estados.php' ? 'active' : ''; ?>" href="estados.php">🗺️ Estados</a>

        <div class="nav-dropdown">
            <button type="button" class="nav-dropdown-btn<?php echo in_array($paginaActual, ['ofrenda.php', 'radionovela.php', 'merch.php'], true) ? ' active' : ''; ?>"
                    id="experienciasBtn" aria-haspopup="true" aria-expanded="false">
                ✨ Experiencias <span class="nav-caret">▾</span>
            </button>
            <div class="nav-dropdown-menu" id="experienciasMenu">
                <a class="<?php echo $paginaActual === 'ofrenda.php' ? 'active' : ''; ?>" href="ofrenda.php">🕯️ Ofrenda Virtual</a>
                <a class="<?php echo $paginaActual === 'radionovela.php' ? 'active' : ''; ?>" href="radionovela.php">📻 Radionovela de terror</a>
                <a class="<?php echo $paginaActual === 'merch.php' ? 'active' : ''; ?>" href="merch.php">🪅 Merch 3D</a>
            </div>
        </div>

        <a class="<?php echo $paginaActual === 'nosotros.php' ? 'active' : ''; ?>" href="nosotros.php">Nuestra historia</a>
        <a class="<?php echo $paginaActual === 'ubicacion.php' ? 'active' : ''; ?>" href="ubicacion.php">Ubicación</a>
        <a class="<?php echo $paginaActual === 'contacto.php' ? 'active' : ''; ?>" href="contacto.php">Contacto</a>
        <a class="cart-link" href="carrito.php">🛒 <span id="cartCount"><?php echo $carritoCantidad; ?></span></a>
        <button class="search-btn" id="searchBtn" type="button" aria-label="Buscar">⌕</button>
    </nav>
</header>