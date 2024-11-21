<?php
// Configuración de la base de datos
define('DB_HOST', 'https://proyectosidgs.com/bd/');
define('DB_USER', 'proye477_chespinema');  // Cambiado a usuario root por defecto de XAMPP
define('DB_PASS', 'proye477_chespinema');      // Contraseña en blanco por defecto de XAMPP
define('DB_NAME', 'proye477_chespinema');

// Función para conectar a la base de datos
function connectDB()
{
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Conexión fallida: " . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        die("<p class='error'>Error de conexión: " . $e->getMessage() . "</p>");
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar Película</title>
    <link rel="stylesheet" href="hola.css">
    <script>
        function validarArchivo() {
            const video = document.getElementById('video');
            const maxSize = 500 * 1024 * 1024; // 500MB en bytes

            if (!video.files || !video.files[0]) return true;

            if (video.files[0].size > maxSize) {
                alert('El archivo es demasiado grande. El tamaño máximo es 500MB.');
                video.value = '';
                return false;
            }

            const sizeMB = Math.round(video.files[0].size / (1024 * 1024) * 100) / 100;
            document.getElementById('fileSize').textContent = `Tamaño del archivo: ${sizeMB}MB`;

            return true;
        }
    </script>
</head>

<body>
    <!--  <header class="header">
        <h1>Insertar película</h1>
    </header>-->

    <?php
    // Verificar límites de PHP
    $upload_max = ini_get('upload_max_filesize');
    $post_max = ini_get('post_max_size');

    /*echo "<div class='config-info'>";
    echo "<p>Configuración actual del servidor:</p>";
    echo "<ul>";
    echo "<li>upload_max_filesize: $upload_max</li>";
    echo "<li>post_max_size: $post_max</li>";
    echo "</ul>";
    echo "</div>";*/

    // Procesar el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $conn = connectDB();

            // Validar y procesar la imagen
            if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== 0) {
                throw new Exception("Error al subir la imagen");
            }

            // Crear directorio de imágenes si no existe
            if (!file_exists('imagenes')) {
                mkdir('imagenes', 0777, true);
                chmod('imagenes', 0777);
            }

            // Procesar la imagen
            $nombre_foto = time() . '_' . basename($_FILES['foto']['name']);
            $ruta_foto = "imagenes/" . $nombre_foto;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_foto)) {
                throw new Exception("Error al mover la imagen al directorio final");
            }

            // Procesar el video
            $nombre_video = '';
            if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {
                // Crear directorio de videos si no existe
                if (!file_exists('videos')) {
                    mkdir('videos', 0777, true);
                    chmod('videos', 0777);
                }

                $allowed_types = ['video/mp4', 'video/webm', 'video/avi'];
                if (!in_array($_FILES['video']['type'], $allowed_types)) {
                    throw new Exception("Tipo de video no permitido. Use MP4, WebM o AVI.");
                }

                $nombre_video = time() . '_' . basename($_FILES['video']['name']);
                $ruta_video = "videos/" . $nombre_video;

                if (!move_uploaded_file($_FILES['video']['tmp_name'], $ruta_video)) {
                    throw new Exception("Error al subir el video");
                }
            }

            // Preparar los datos para la base de datos
            $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
            $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
            $estatus = 1;
            $url = mysqli_real_escape_string($conn, $_POST['url']);
            $fk_genero = $_POST['fk_genero'];
            $fecha_subida = date("Y-m-d H:i:s");

            // Insertar en la base de datos
            $sql = "INSERT INTO pelicula (titulo, descripcion, foto, video_local, url, estatus, fecha_subida, fk_genero) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conn->error);
            }

            $stmt->bind_param("sssssssi", $titulo, $descripcion, $nombre_foto, $nombre_video, $url, $estatus, $fecha_subida, $fk_genero);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar en la base de datos: " . $stmt->error);
            }

            echo "<p class='success'>Película insertada correctamente.</p>";
            header("refresh:3;url=index.php");
        } catch (Exception $e) {
            echo "<p class='error'>" . $e->getMessage() . "</p>";
        } finally {
            if (isset($stmt)) $stmt->close();
            if (isset($conn)) $conn->close();
        }
    }
    ?>

    <main>
        <section class="form-section">
            <h2>Insertar Película</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" onsubmit="return validarArchivo() && subirPelicula(this)">
                <div class="form-group">
                    <label for="titulo">Título:</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" required></textarea>
                </div>

                <div class="form-group">
                    <label for="foto">Imagen de portada:</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                </div>

                <div class="form-group">
                    <label for="video">Archivo de video (máx. 500MB):</label>
                    <input type="file" id="video" name="video" accept="video/*" onchange="validarArchivo()">
                    <div id="fileSize"></div>
                    <small>Formatos permitidos: MP4, WebM, AVI</small>
                </div>

                <div class="form-group">
                    <label for="url">URL alternativa de la película:</label>
                    <input type="url" id="url" name="url" placeholder="http://example.com">
                    <small>Opcional si sube un archivo de video</small>
                </div>

                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select id="genero" name="fk_genero" required>
                        <?php
                        try {
                            $conn = connectDB();
                            $sql = "SELECT pk_genero, nombre_genero FROM generos";
                            $result = $conn->query($sql);

                            if (!$result) {
                                throw new Exception("Error en la consulta de géneros: " . $conn->error);
                            }

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['pk_genero']) . '">' .
                                        htmlspecialchars($row['nombre_genero']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay géneros disponibles</option>';
                            }
                        } catch (Exception $e) {
                            echo '<option value="">Error al cargar géneros</option>';
                            echo "<p class='error'>" . $e->getMessage() . "</p>";
                        } finally {
                            if (isset($conn)) $conn->close();
                        }
                        ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Insertar Película</button>
                    <a class="btn-cancel" type="button" onclick="window.location.href='index.php'">Cancelar</a>
                </div>
            </form>

            <!-- Barra de carga -->
            <div id="barraCarga" style="display: none;">
                <div id="progreso" style="height: 30px; background-color: red; width: 0%; margin-top: 20px"></div>
                <p id="porcentaje">0%</p>
            </div>
        </section>
    </main>

</body>
<script src="js/barracarga.js"></script>

</html>