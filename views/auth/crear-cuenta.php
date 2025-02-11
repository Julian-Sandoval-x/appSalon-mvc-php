<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php
include_once __DIR__ . '/../templates/alertas.php'
?>

<form class="formulario" method="POST" action="/crear-cuenta">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Tú Nombre" value="<?php echo s($user->nombre); ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" placeholder="Tú Apellido" value="<?php echo s($user->apellido); ?>">
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" placeholder="Tú Telefono" value="<?php echo s($user->telefono); ?>">
    </div>

    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Tú E-mail" value="<?php echo s($user->email); ?>">
    </div>

    <div class="campo">
        <label for="password">password</label>
        <input type="password" id="password" name="password" placeholder="Tú Password">
    </div>

    <input type="submit" value="crear cuenta" class="boton">

</form>

<div class="actions">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/forgot">¿Olvidaste tu contraseña?</a>
</div>