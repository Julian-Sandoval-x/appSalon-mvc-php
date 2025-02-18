<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\User;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $user = User::where('email', $auth->email);

                if ($user) {
                    // Verificamos el password y que este verificado
                    if ($user->comprobarPasswordAndConfirmado($auth->password)) {
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $user->id;
                        $_SESSION['nombre'] = $user->nombre . " " . $user->apellido;
                        $_SESSION['email'] = $user->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($user->admin === '1') {
                            $_SESSION['admin'] = $user->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    User::setAlerta('error', 'El usuario no existe');
                }
            }
        }
        $alertas = User::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }


    public static function logout()
    {
        session_start();

        $_SESSION = [];

        header('Location: /');  // Redireccionar al inicio
    }

    public static function forgot(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);
            $user = $auth->validarEmail();

            if (empty($alertas)) {
                $user = User::where('email', $auth->email);

                if ($user && $user->confirmado === '1') {
                    // Generamos un token
                    $user->generarToken();
                    $user->guardar();

                    // Enviamos el email
                    $email = new Email($user->email, $user->nombre, $user->token);
                    $email->enviarRecuperacion();


                    // Alerta de exito
                    User::setAlerta('exito', 'Revisa tu email para cambiar tu contraseña');
                } else {
                    User::setAlerta('error', 'El email no existe o no esta confirmado');
                }
            }
        }

        $alertas = User::getAlertas();
        $router->render('auth/recovery-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recovery(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscamos al usuario por su token
        $user = User::where('token', $token);

        if (empty($user)) {
            User::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y validarlo
            $password = new User($_POST);

            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                // Hashear el password
                $user->password = null;
                $user->password = $password->password;
                $user->hashPassword();
                $user->token = null;
                $resultado = $user->guardar();

                if ($resultado) {
                    header('Location: /');
                }
            }
        }


        $alertas = User::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crearCuenta(Router $router)
    {

        // Creamos una nueva instancia de usuario
        $user = new User;

        // Arreglo de alertas
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD']  === 'POST') {

            $user->sincronizar($_POST);

            $alertas = $user->validarNewAccount();

            // Revisar que alerta este vacio
            if (empty($alertas)) {
                // Verificar si el usuario ya existe
                $resultado = $user->existeUser();

                if ($resultado->num_rows) {
                    $alertas = User::getAlertas();
                } else {
                    // Crear el usuario

                    // Hashear el password
                    $user->hashPassword();

                    // Generar un token
                    $user->generarToken();

                    // Enviar el email
                    $email = new Email($user->email, $user->nombre, $user->token);

                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $user->guardar();
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'user' => $user,
            'alertas' => $alertas,
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);

        $user = User::where('token', $token);

        if (empty($user)) {
            // Mostrar mensaje de error
            User::setAlerta('error', 'Token No Valido');
        } else {
            // Activamos la cuenta
            $user->confirmado = 1;

            // Eliminamos el token
            $user->token = null;

            // Actualizamos el registro
            $user->guardar();
            User::setAlerta('exito', "Cuenta Confirmada, Correctamente");
        }

        // Obtener alertas
        $alertas = User::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
