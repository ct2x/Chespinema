<?php
require_once './model/Usuario.php';

class RegistroController {
    public function mostrarFormulario() {
        require './index.php';
    }

    public function registrarUsuario() {
        if (isset($_POST['nombre_usuario']) && isset($_POST['correo']) && isset($_POST['tel']) && isset($_POST['contrasena'])) {
            $nombre_usuario = $_POST['nombre_usuario'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            $tel = $_POST['tel'];

            $usuario = new Usuario();
            $resultado = $usuario->registrar($nombre_usuario, $correo, $tel, $contrasena);
            if ($resultado === true) {
                $_SESSION['registro_success'] = 'Registro exitoso';
                header("Location: ./index.php");
                exit();
            } else {
                $_SESSION['registro_error'] = 'El nombre de usuario o correo ya está en uso';
                header("Location: ./index.php");
                exit();
            }
        }
    }
}
