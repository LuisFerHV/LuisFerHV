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
    // Aplica el tema guardado lo antes posible, para que no "parpadee" el tema de Muertos un instante.
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
        <a class="<?php echo $paginaActual === 'nosotros.php' ? 'active' : ''; ?>" href="nosotros.php">Nuestra historia</a>
        <a class="<?php echo $paginaActual === 'estados.php' ? 'active' : ''; ?>" href="estados.php">🗺️ Estados</a>
        <a class="<?php echo $paginaActual === 'ubicacion.php' ? 'active' : ''; ?>" href="ubicacion.php">Ubicación</a>
        <a class="<?php echo $paginaActual === 'contacto.php' ? 'active' : ''; ?>" href="contacto.php">Contacto</a>
        <a class="cart-link" href="carrito.php">🛒 <span id="cartCount"><?php echo $carritoCantidad; ?></span></a>
        <button class="materias-btn" id="materiasBtn" type="button" aria-label="Mis materias" aria-haspopup="true" aria-expanded="false">📚</button>
        <button class="search-btn" id="searchBtn" type="button" aria-label="Buscar">⌕</button>
    </nav>
</header>

<div class="materias-overlay" id="materiasOverlay"></div>
<aside class="materias-panel" id="materiasPanel" aria-hidden="true" aria-label="Panel de materias">
    <div class="materias-header">
        <h2>📚 Mis materias</h2>
        <button class="materias-cerrar" id="materiasCerrar" type="button" aria-label="Cerrar panel de materias">✕</button>
    </div>
    <div class="materias-lista" id="materiasLista">
        <p class="materias-cargando">Cargando materias…</p>
    </div>
</aside>