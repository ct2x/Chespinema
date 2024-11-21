<?php
include 'conexion.php';

// Obtener el término de búsqueda
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Preparar la consulta SQL con búsqueda parcial
$query = "SELECT pk_pelicula, titulo, foto FROM pelicula 
          WHERE estatus = 1 
          AND titulo LIKE ? 
          ORDER BY fecha_subida DESC";

$stmt = $conn->prepare($query);
$searchTerm = "%$searchTerm%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$peliculas = array();

while ($row = $result->fetch_assoc()) {
    $pelicula = array(
        'id' => $row['pk_pelicula'],
        'titulo' => $row['titulo'],
        'foto' => htmlspecialchars($row['foto'])  // Solo guardamos el nombre del archivo
    );
    $peliculas[] = $pelicula;
}

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($peliculas);

$stmt->close();
$conn->close();