<?php
require_once './config/conexion.php';

class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function verificarCredenciales($nombre_usuario, $contrasena) {
        $query = "SELECT pk_usuarios, contrasena FROM " . $this->table . " WHERE usuario = :nombre_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario['pk_usuarios']; // Devuelve el ID del usuario si las credenciales son correctas
        }
        return false; // Devuelve falso si no coincide
    }


    // Método para registrar un nuevo usuario
    public function registrar($nombre_usuario, $correo, $tel, $contrasena) {
        // Verificar si el usuario ya existe
        $query = "SELECT pk_usuarios FROM " . $this->table . " WHERE usuario = :nombre_usuario OR correo = :correo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->fetch()) {
            return "El nombre de usuario o correo ya están en uso.";
        }

        // Insertar nuevo usuario
        $query = "INSERT INTO " . $this->table . " (usuario, correo, tel, contrasena) VALUES (:nombre_usuario, :correo, :tel, :contrasena)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':tel', $tel);

        // Encriptar la contraseña antes de guardarla
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt->bindParam(':contrasena', $contrasena_hash);

        if ($stmt->execute()) {
            return true; // Registro exitoso
        } else {
            return "Error al registrar el usuario. Inténtalo de nuevo.";
        }
    }
}
