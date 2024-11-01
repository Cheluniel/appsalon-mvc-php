<h1 class="nombre-pagina">Actualizar Servicios</h1>
<p class="descripcion-pagina">Modifica los valores del formulario para actualizar el Servicio</p>

<?php
include_once __DIR__ . '/../templates/barras.php';
include_once __DIR__ . '/../templates/alertas.php';
?>

<form method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?>
    
    <input type="submit" class="boton" value="Actualizar Servicio"> 
</form>