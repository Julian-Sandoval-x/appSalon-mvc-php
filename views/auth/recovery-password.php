<h1 class="nombre-pagina">Olvide mi contraseña</h1>
<p class="descripcion-pagina">Restablece tu contraseña escribiendo tu E-mail a continuación</p>

<?php
include_once __DIR__ . '/../templates/alertas.php';
?>

<form action="/forgot" class="formulario" method="POST">

    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="Tu E-mail" required>
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones">
</form>

<div class="actions">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/">¿Ya tienes cuenta? Iniciar Sesion</a>
</div>