<h1 class="nombre-pagina">Nuevo Servicios</h1>
<p class="descripcion-pagina">Llena todos los valores del formulario para crear un nuevo Servicio</p>

<?php
include_once __DIR__ . '/../templates/barras.php';
include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/servicios/crear" method="POST" class="formulario">
    <?php include_once __DIR__ . '/formulario.php'; ?>
    
    <input type="submit" class="boton" value="Guardar Servicio"> 
</form>