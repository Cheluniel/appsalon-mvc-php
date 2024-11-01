<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        $auth = new Usuario();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // VERIFICAR SI EL USUARIO EXISTE 
                $usuario = Usuario::where('email', $auth->email);
                

                // SI EL USUARIO EXISTE
                if($usuario) {
                    // VERIFICAR EL PASSWORD
                    if($usuario->checkPasswordAndVerificado($auth->password)) {
                        // AUTENTICAR EL USUARIO
                        isSession();
                        
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // REDIRECCIONAR AL USUARIO
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('location: /admin');
                        } else {
                            header('location: /cita');
                        }

                        debuguear($_SESSION);
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }
    public static function logout() {
        isSession();
        $_SESSION = [];
        header('location: /');
    }
    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $usuario = $auth->where('email', $auth->email);

                if($usuario && $usuario->confirmado === '1') {
                    // GENERAR UN TOKEN
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    // ENVIAR EL MAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // ALERTAS
                    Usuario::setAlerta('exito', 'Revisa la casilla de tu correo');
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado o no confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $error = false;

        // BUSCAR USUARIO POR SU TOKEN
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token inválido');
            $error = true;
        }

        // debuguear($usuario);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // LEER EL NUEVO PASSWORD Y GUARDARLO
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            
            if(empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();

                if($resultado) {
                    header('location: /cita');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router) {
        $usuario = new Usuario;

        // ALERTAS VACÍAS
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // REVISAR QUE ALERTA ESTÉ VACÍO
            if(empty($alertas)) {
                // VERIFICAR QUE EL USUARIO NO ESTÉ REGISTRADO
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // HASHEAR EL PASSWORD
                    $usuario->hashPassword();

                    // CREAR UN TOKEN ÚNICO
                    $usuario->crearToken();

                    // ENVIAR EL EMAIL
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // debuguear($usuario);

                    // CREAR EL USUARIO
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('location: /mensaje');
                    } 
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
            
        ]);
    }

    public static function mensaje(Router $router) {
        
        $router->render('auth/mensaje');
    }
    
    public static function confirmar(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            // MOSTRAR MENSAJE DE ERROR
            Usuario::setAlerta('error', 'El token no es válido');
        } else {
            // MODIFICAR USUARIO A CONFIRMADO
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();

            // MOSTRAR MENSAJE DE EXITO
            Usuario::setAlerta('exito', 'Cuenta creada con exito');

        }
        // MOSTRAR ALERTAS
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}

