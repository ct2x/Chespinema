<?php
require_once 'model/Usuario.php';

class LoginController {
    public function login() {
        if (isset($_POST['nombre_usuario']) && isset($_POST['contrasena'])) {
            $nombre_usuario = $_POST['nombre_usuario'];
            $contrasena = $_POST['contrasena'];

            $usuario = new Usuario();
            $usuario_id = $usuario->verificarCredenciales($nombre_usuario, $contrasena);

            if ($usuario_id) {
                session_start();
                $_SESSION['usuario_id'] = $usuario_id;
                $_SESSION['nombre_usuario'] = $nombre_usuario;
                
                header("Location: ./nose.php"); // Redirige al perfil
                exit();
            } else {
                $error = "Nombre de usuario o contraseña incorrectos.";
            }
        } else {
            //aaaaaaaaaaaaaaa
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: ./nose.php");
        exit();
    }
}
