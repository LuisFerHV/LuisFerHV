<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Radionovela de terror | El Mictlán</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php include "header.php"; ?>

<section class="page-hero ofrenda-hero">
    <div class="papel-picado" aria-hidden="true">
        <?php for ($i = 0; $i < 14; $i++): ?><span></span><?php endfor; ?>
    </div>
    <p>EL MICTLÁN · RELATOS</p>
    <h1>Radionovela de terror</h1>
    <span>Una leyenda para escuchar con la luz apagada y un café en la mano.</span>
</section>

<section class="radio section">
    <div class="radio-aparato">
        <div class="radio-rejilla" aria-hidden="true">
            <?php for ($i = 0; $i < 7; $i++): ?><span></span><?php endfor; ?>
        </div>

        <div class="radio-info">
            <h2 id="radioTitulo">El último cliente de El Mictlán</h2>
            <p class="radio-capitulo">Episodio 1 · Duración aprox. 12 min</p>
        </div>

        <audio id="radioAudio" preload="metadata" src="imn/radionovela.mp3"></audio>

        <div class="radio-controles">
            <button type="button" id="radioPlay" class="radio-play" aria-label="Reproducir">▶</button>

            <div class="radio-progreso">
                <span id="radioActual">0:00</span>
                <input type="range" id="radioBarra" value="0" min="0" max="100" step="0.1">
                <span id="radioDuracion">0:00</span>
            </div>

            <div class="radio-volumen">
                <span>🔊</span>
                <input type="range" id="radioVolumen" min="0" max="1" step="0.01" value="1">
            </div>
        </div>

        <p id="radioAviso" class="radio-aviso" hidden>
            📻 Todavía no hay audio cargado aquí. Agrega tu archivo como
            <code>imn/radionovela.mp3</code> y este reproductor funcionará solo.
        </p>
    </div>

    <div class="radio-sinopsis">
        <h2>De qué trata</h2>
        <p>
            Cuentan los vecinos de Ciudad Azteca que, cada 1 de noviembre pasada la medianoche,
            la campanita de la puerta de El Mictlán suena una vez más aunque el local ya esté
            cerrado. Quien se queda a limpiar jura haber despachado un café de olla a un cliente
            que nadie más vio entrar... y que no dejó pago, solo una marca de ceniza en la mesa.
        </p>
        <p class="radio-creditos">Relato original de El Mictlán · Guion disponible para que lo grabes tú mismo.</p>
    </div>
</section>

<?php include "footer.php"; ?>
<script src="script.js"></script>
</body>
</html>