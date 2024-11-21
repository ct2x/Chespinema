<?php
include 'conexion.php';

if (isset($_GET['genero'])) {
    $genero_id = intval($_GET['genero']);
    
    // Si el género es 0, devolver todas las películas
    if ($genero_id === 0) {
        $query = "SELECT pk_pelicula, titulo, foto FROM pelicula WHERE estatus = 1";
    } else {
        $query = "SELECT pk_pelicula, titulo, foto FROM pelicula 
                 WHERE estatus = 1 AND fk_genero = ?";
    }
    
    $stmt = $conn->prepare($query);
    
    if ($genero_id !== 0) {
        $stmt->bind_param("i", $genero_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $peliculas = array();
    while ($row = $result->fetch_assoc()) {
        $peliculas[] = array(
            'pk_pelicula' => $row['pk_pelicula'],
            'titulo' => $row['titulo'],
            'foto' => $row['foto']
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($peliculas);
    
    $stmt->close();
}

$conn->close();
?>