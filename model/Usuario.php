<?php
require_once 'config/conexion.php';

class Usuario {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function obtenerDatosUsuario($nombre_usuario) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND estatus = 1");
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        }
        return null;
    }

    public function verificarCredenciales($nombre_usuario, $contrasena) {
        $usuario_data = $this->obtenerDatosUsuario($nombre_usuario);
        
        if ($usuario_data && password_verify($contrasena, $usuario_data['contrasena'])) {
            return $usuario_data['pk_usuarios'];
        }
        return false;
    }

    public function registrar($nombre_usuario, $correo, $tel, $contrasena) {
        // Verificar si el usuario o correo ya existe
        $stmt = $this->conn->prepare("SELECT usuario FROM usuarios WHERE usuario = ? OR correo = ?");
        $stmt->bind_param("ss", $nombre_usuario, $correo);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            return "El nombre de usuario o correo ya está en uso";
        }

        // Hash de la contraseña
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        
        // Insertar nuevo usuario
        $stmt = $this->conn->prepare("INSERT INTO usuarios (usuario, contrasena, correo, tel, estatus, fecha_creacion, tipo_usuario) VALUES (?, ?, ?, ?, 1, NOW(), 0)");
        $stmt->bind_param("ssss", $nombre_usuario, $hash, $correo, $tel);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error al registrar el usuario: " . $this->conn->error;
        }
    }

    public function actualizarPerfil($usuario_id, $datos) {
        $query_parts = [];
        $types = "";
        $values = [];
    
        if (isset($datos['nombre_usuario'])) {
            $query_parts[] = "usuario = ?";
            $types .= "s";
            $values[] = $datos['nombre_usuario'];
        }
    
        if (isset($datos['correo'])) {
            $query_parts[] = "correo = ?";
            $types .= "s";
            $values[] = $datos['correo'];
        }
    
        if (isset($datos['tel'])) {
            $query_parts[] = "tel = ?";
            $types .= "s";
            $values[] = $datos['tel'];
        }
    
        if (isset($datos['foto'])) {
            $query_parts[] = "foto = ?";
            $types .= "s";
            $values[] = $datos['foto'];
        }
    
        if (empty($query_parts)) {
            return false;
        }
    
        $query = "UPDATE usuarios SET " . implode(", ", $query_parts) . " WHERE pk_usuarios = ?";
        $types .= "i";
        $values[] = $usuario_id;
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
}