<?php
require_once './model/Usuario.php';

class RegistroController {
    public function mostrarFormulario() {
        require './nose.php';
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
                header("Location: ./nose.php"); // Redirige al login tras el registro exitoso
                exit();
            } else {
                $error = $resultado; // Muestra el mensaje de error de registro
            }
        }
        require './nose.php';
    }
}
