<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia Sesión con tus datos</p>

<?php
include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/" method="post" class="formulario">
    <div class="campo">
        <input type="email" placeholder="Tu E-mail" name="email" value="<?php echo s($auth->email); ?>">
    </div>
    <div class="campo">
        <input type="password" placeholder="Tu Contraseña" name="password">
    </div>
    
    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>