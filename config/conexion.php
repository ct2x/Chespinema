<?php
class Database {
    private $host = "https://proyectosidgs.com/bd/";
    private $db_name = "proye477_chespinema";
    private $username = "proye477_chespinema";
    private $password = "proye477_chespinema";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}