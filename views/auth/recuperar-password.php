<h1 class="nombre-pagina">Reestablecer contraseña</h1>
<p class="descripcion-pagina">Coloca tu nueva contraseña a continuación</p>

<?php
include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if ($error)
    return; ?>
    
<form class="formulario" method="post">
    <div class="campo">
        <input type="password" name="password" placeholder="Nueva Contraseña">
    </div>
    
    <input type="submit" class="boton" value="Reestablecer contraseña">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>