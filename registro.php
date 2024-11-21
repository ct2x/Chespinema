<?php
// registro.php
session_start();
require_once 'conexion.php';

class RegistroHandler {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registrarUsuario($nombre_usuario, $correo, $tel, $contrasena) {
        // Verificar si el usuario o correo ya existen
        $stmt = $this->conn->prepare("SELECT pk_usuarios FROM usuarios WHERE usuario = ? OR correo = ?");
        $stmt->bind_param("ss", $nombre_usuario, $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return false; // Usuario o correo ya existen
        }

        // Insertar nuevo usuario
        $query = "INSERT INTO usuarios (usuario, correo, tel, contrasena, estatus, tipo_usuario, fecha_creacion) 
                 VALUES (?, ?, ?, ?, 1, 1, NOW())";
        $stmt = $this->conn->prepare($query);
        
        // Encriptar la contraseña
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        
        // Usar bind_param para MySQLi
        $stmt->bind_param("ssss", $nombre_usuario, $correo, $tel, $contrasena_hash);
        return $stmt->execute();
    }
}

// Procesar el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre_usuario']) && isset($_POST['correo']) && 
        isset($_POST['tel']) && isset($_POST['contrasena'])) {
        
        $registro = new RegistroHandler($conn);
        $resultado = $registro->registrarUsuario(
            $_POST['nombre_usuario'],
            $_POST['correo'],
            $_POST['tel'],
            $_POST['contrasena']
        );

        if ($resultado) {
            $_SESSION['registro_success'] = true;
        } else {
            $_SESSION['registro_error'] = true;
        }

        header("Location: index.php");
        exit();
    }
}