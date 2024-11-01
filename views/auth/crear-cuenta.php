<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Completa el formulario para crear tu cuenta</p>

<?php
include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/crear-cuenta" method="post" class="formulario">
    <div class="campo">
        <input type="text" placeholder="Tu Nombre" name="nombre" value="<?php echo s($usuario->nombre); ?>">
    </div>
    <div class="campo">
        <input type="text" placeholder="Tu Apellido" name="apellido" value="<?php echo s($usuario->apellido); ?>">
    </div>
    <div class="campo">
        <input type="tel" placeholder="Tu Teléfono" name="telefono" value="<?php echo s($usuario->telefono); ?>">
    </div>
    <div class="campo">
        <input type="email" placeholder="Tu E-mail" name="email" value="<?php echo s($usuario->email); ?>">
    </div>
    <div class="campo">
        <input type="password" placeholder="Tu Contraseña" name="password">
    </div>
    
    <input type="submit" class="boton" value="Crear Cuenta">
    
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>