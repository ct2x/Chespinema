<?php
include "conexion.php";

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$estatus = $_POST['estatus'];
$url = $_POST['url'];
$fk_genero = $_POST['fk_genero'];
$fecha_subida = date("Y-m-d H:i:s"); // Fecha y hora actual

// Manejo de la imagen
$foto = $_FILES['foto']['name'];
$ruta_temp = $_FILES['foto']['tmp_name'];
$carpeta_destino = "imagenes/" . $foto;

// Mover la imagen a la carpeta de destino
if (move_uploaded_file($ruta_temp, $carpeta_destino)) {
    // Inserción en la base de datos
    $sql = "INSERT INTO pelicula (titulo, descripcion, foto, url, estatus, fecha_subida, fk_genero) 
            VALUES ('$titulo', '$descripcion', '$foto', '$url', '$estatus', '$fecha_subida', '$fk_genero')";

    if ($conn->query($sql) === TRUE) {
        header("location: nose.php");
    } else {
        echo "<p>Error al insertar la película: " . $conn->error . "</p>";
    }
} else {
    echo "<p>Error al subir la imagen.</p>";
}

$conn->close();
?>
