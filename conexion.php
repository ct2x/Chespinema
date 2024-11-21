<?php
$servername = "https://proyectosidgs.com/bd/";
$username = "proye477_chespinema";
$password = "proye477_chespinema";
$database = "proye477_chespinema";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

?>
