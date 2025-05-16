<?php

namespace Controllers;

use Classes\Email;
use Model\User;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el User
                $User = User::where('email', $auth->email);

                if ($User) {
                    // Verificar el password
                    if ($User->comprobarPasswordAndConfirmado($auth->password)) {
                        // Autenticar el User
                        session_start();

                        $_SESSION['id'] = $User->id;
                        $_SESSION['nombre'] = $User->nombre . " " . $User->apellido;
                        $_SESSION['email'] = $User->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($User->admin === "1") {
                            $_SESSION['admin'] = $User->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    User::setAlerta('error', 'User no encontrado');
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
        header('Location: /');
    }

    public static function olvide(Router $router)
    {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new User($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $User = User::where('email', $auth->email);

                if ($User && $User->confirmado === "1") {

                    // Generar un token
                    $User->crearToken();
                    $User->guardar();

                    //  Enviar el email
                    $email = new Email($User->email, $User->nombre, $User->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    User::setAlerta('exito', 'Revisa tu email');
                } else {
                    User::setAlerta('error', 'El User no existe o no esta confirmado');
                }
            }
        }

        $alertas = User::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);

        // Buscar User por su token
        $User = User::where('token', $token);

        if (empty($User)) {
            User::setAlerta('error', 'Token No Válido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo

            $password = new User($_POST);
            $alertas = $password->validarPassword();

            if (empty($alertas)) {
                $User->password = null;

                $User->password = $password->password;
                $User->hashPassword();
                $User->token = null;

                $resultado = $User->guardar();
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

    public static function crear(Router $router)
    {
        $User = new User;

        // Alertas vacias
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $User->sincronizar($_POST);
            $alertas = $User->validarNuevaCuenta();

            // Revisar que alerta este vacio
            if (empty($alertas)) {
                // Verificar que el User no este registrado
                $resultado = $User->existeUser();

                if ($resultado->num_rows) {
                    $alertas = User::getAlertas();
                } else {
                    // Hashear el Password
                    $User->hashPassword();

                    // Generar un Token único
                    $User->crearToken();

                    // Enviar el Email
                    $email = new Email($User->nombre, $User->email, $User->token);
                    $email->enviarConfirmacion();

                    // Crear el User
                    $resultado = $User->guardar();
                    // debuguear($User);
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'User' => $User,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = s($_GET['token']);
        $User = User::where('token', $token);

        if (empty($User)) {
            // Mostrar mensaje de error
            User::setAlerta('error', 'Token No Válido');
        } else {
            // Modificar a User confirmado
            $User->confirmado = "1";
            $User->token = null;
            $User->guardar();
            User::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obtener alertas
        $alertas = User::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
