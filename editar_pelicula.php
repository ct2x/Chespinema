<?php
session_start(); // Debe estar al principio
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pk_pelicula = $_POST['pk_pelicula'];
    $titulo = $_POST['titulo'];
    $categoria = $_POST['categoria'];

    // Manejar la actualización de la imagen si se carga una nueva
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoNombre = basename($_FILES['foto']['name']);
        move_uploaded_file($fotoTmp, "imagenes/$fotoNombre");
        $query = "UPDATE pelicula SET titulo = '$titulo', fk_genero = (SELECT pk_genero FROM generos WHERE nombre_genero = '$categoria'), foto = '$fotoNombre' WHERE pk_pelicula = '$pk_pelicula'";
    } else {
        $query = "UPDATE pelicula SET titulo = '$titulo', fk_genero = (SELECT pk_genero FROM generos WHERE nombre_genero = '$categoria') WHERE pk_pelicula = '$pk_pelicula'";
    }

    if ($conn->query($query) === TRUE) {
        // Establecer la variable de sesión antes de redirigir
        $_SESSION['pelicula_success'] = true;
        header("Location: panel_admin.php"); // Redirige al panel de administrador
        exit(); // Detener la ejecución del script
    } else {
        // Mostrar error con Sweet Alert
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al editar la película: " . $conn->error . "',
                confirmButtonText: 'Aceptar'
            });
        </script>";
    }

    $conn->close();
}
?>
