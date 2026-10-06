<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contacto | El Mictlán</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include "header.php"; ?>
<section class="page-hero"><p>EL MICTLÁN</p><h1>Contacto</h1><span>Estamos para atenderte.</span></section>
<section class="contact section">
    <div class="contact-copy">
        <h2>Hablemos</h2>
        <p>¿Tienes dudas sobre el menú, pedidos o el proyecto? Puedes utilizar este formulario como interfaz de contacto.</p>
        <div class="contact-detail">📞 <span>55 4852 9756</span></div>
        <div class="contact-detail">✉️ <span>contacto@elmictlan.mx</span></div>
        <div class="contact-detail">📍 <span>Ciudad Azteca, Ecatepec, Estado de México</span></div>
    </div>
    <form class="contact-form" onsubmit="event.preventDefault(); mostrarMensajeContacto();">
        <label>Nombre<input type="text" required></label>
        <label>Correo<input type="email" required></label>
        <label>Mensaje<textarea rows="6" required></textarea></label>
        <button class="btn" type="submit">Enviar mensaje</button>
    </form>
</section>
<div id="contactToast" class="toast">Mensaje preparado. Conecta este formulario a correo o PHP cuando quieras.</div>
<?php include "footer.php"; ?><script src="script.js"></script>
</body></html>
