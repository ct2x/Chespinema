<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Película</title>
    <link rel="stylesheet" href="hola.css">
</head>
<body>
    <header class="header">
        <h1>Insertar película</h1>
    </header>

    <main>
        <form action="insertar_peliculas.php" method="post" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>
            
            <label for="foto">Imagen:</label>
            <input type="file" id="foto" name="foto" accept="image/*" required>
            
            <label for="url">URL de la película:</label>
            <input type="url" id="url" name="url" required>
            
            <label for="estatus">Estatus:</label>
            <select id="estatus" name="estatus">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
            
            <label for="genero">Género:</label>
            <select id="genero" name="fk_genero" required>
                <?php
                // Conectar a la base de datos
                include "conexion.php";
                
                // Verificar la conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Consulta para obtener los géneros
                $sql = "SELECT pk_genero, nombre_genero FROM generos";
                $result = $conn->query($sql);

                // Manejo de errores de la consulta
                if (!$result) {
                    echo "Error en la consulta: " . $conn->error;
                } else {
                    // Llenar el select con géneros
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['pk_genero'] . '">' . $row['nombre_genero'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay géneros disponibles</option>';
                    }
                }

                // Cerrar la conexión a la base de datos
                $conn->close();
                ?>
            </select>
            
            <button type="submit">Insertar Película</button>
        </form>

        <?php
        // Procesar el formulario al enviarlo
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Conectar a la base de datos
            $conn = new mysqli("localhost", "usuario", "contraseña", "chespinema");

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
            $carpeta_destino = "imagenes/" . $foto; // Carpeta donde guardar las imágenes

            // Mover la imagen a la carpeta de destino
            if (move_uploaded_file($ruta_temp, $carpeta_destino)) {
                // Inserción en la base de datos
                $sql = "INSERT INTO pelicula (titulo, descripcion, foto, url, estatus, fecha_subida, fk_genero) 
                        VALUES ('$titulo', '$descripcion', '$foto', '$url', '$estatus', '$fecha_subida', '$fk_genero')";

                if ($conn->query($sql) === TRUE) {
                    header(location: "nose.php");
                } else {
                    echo "<p>Error al insertar la película: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>Error al subir la imagen.</p>";
            }

            $conn->close();
        }
        ?>
    </main>
</body>
</html>
