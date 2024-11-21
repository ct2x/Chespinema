<?php
require_once 'model/Usuario.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class LoginController {
    public function login() {
        if (isset($_POST['nombre_usuario']) && isset($_POST['contrasena'])) {
            $nombre_usuario = $_POST['nombre_usuario'];
            $contrasena = $_POST['contrasena'];
    
            $usuario = new Usuario();
            $usuario_data = $usuario->obtenerDatosUsuario($nombre_usuario);
    
            if ($usuario_data && password_verify($contrasena, $usuario_data['contrasena'])) {
                session_start();
                // Almacenar todos los datos necesarios en la sesión
                $_SESSION['usuario_id'] = $usuario_data['pk_usuarios'];
                $_SESSION['nombre_usuario'] = $usuario_data['usuario'];
                $_SESSION['correo'] = $usuario_data['correo'];
                $_SESSION['tel'] = $usuario_data['tel'];
                $_SESSION['foto_perfil'] = isset($usuario_data['imagen']) ? $usuario_data['imagen'] : 'default.webp';
                $_SESSION['tipo_usuario'] = $usuario_data['tipo_usuario'];
                $_SESSION['login_success'] = true;
    
                // Redirigir según el tipo de usuario
                if ($_SESSION['tipo_usuario'] == 2) {
                    header("Location: ./panel_admin.php");
                } else {
                    header("Location: ./index.php");
                }
                exit();
            } else {
                $_SESSION['login_error'] = true;
                header("Location: ./index.php");
                exit();
            }
        }
    }
    

    public function logout() {
        session_start();
        // Guardar el flag de logout antes de destruir la sesión
        $logout_success = true;
        
        session_unset();
        session_destroy();
        
        session_start();
        $_SESSION['logout_success'] = $logout_success;
        header("Location: ./index.php");
        exit();
    }
}

class RegistroController {
    public function registrarUsuario() {
        if (isset($_POST['nombre_usuario']) && isset($_POST['correo']) && 
            isset($_POST['tel']) && isset($_POST['contrasena'])) {
            
            $nombre_usuario = trim($_POST['nombre_usuario']);
            $correo = trim($_POST['correo']);
            $contrasena = $_POST['contrasena'];
            $tel = trim($_POST['tel']);

            // Validaciones básicas
            if (empty($nombre_usuario) || empty($correo) || empty($contrasena) || empty($tel)) {
                session_start();
                $_SESSION['registro_error'] = 'Todos los campos son obligatorios';
                header("Location: ./index.php");
                exit();
            }

            // Validar formato de correo
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                session_start();
                $_SESSION['registro_error'] = 'El formato del correo no es válido';
                header("Location: ./index.php");
                exit();
            }

            $usuario = new Usuario();
            $resultado = $usuario->registrar($nombre_usuario, $correo, $tel, $contrasena);
            
            session_start();
            if ($resultado === true) {
                $_SESSION['registro_success'] = true;
                header("Location: ./index.php");
                exit();
            } else {
                $_SESSION['registro_error'] = $resultado;
                header("Location: ./index.php");
                exit();
            }
        }
    }
}