<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia Sesion</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="text" type="email" id="email" placeholder="Tú Email" name="email" />
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" type="password" id="password" placeholder="Tú Password" name="password" />
    </div>

    <input type="submit" class="boton" value="Iniciar Sesion" />
</form>

<div class="actions">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/forgot">¿Olvidaste tu contraseña?</a>
</div>