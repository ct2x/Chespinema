<?php
session_start();
include 'conexion.php';

// Obtener el ID de la película de la URL
$id_pelicula = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los detalles de la película específica
$query = "SELECT p.*, g.nombre_genero 
          FROM pelicula p 
          LEFT JOIN generos g ON p.fk_genero = g.pk_genero 
          WHERE p.pk_pelicula = ? AND p.estatus = 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pelicula);
$stmt->execute();
$result = $stmt->get_result();
$pelicula = $result->fetch_assoc();

// Si no se encuentra la película, redirigir a la página principal
if (!$pelicula) {
    header("Location: index.php");
    exit();
}

// Consulta para obtener los géneros (para el menú de navegación)
$query_generos = "SELECT pk_genero, nombre_genero FROM generos";
$result_generos = $conn->query($query_generos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pelicula['titulo']); ?> - Chespinema</title>
    <link rel="icon" href="logo.jpg">
    <link rel="stylesheet" href="ver_pelicula.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <a href="index.php" style="text-decoration: none; color: white;">
            <div class="logo">
                <img src="logo.jpg" alt="Logo">
                <h1>Chespinema</h1>
            </div>
        </a>
        <?php
        if (isset($_SESSION['nombre_usuario'])) {
            ?>
            <div class="perfil">
                <a href="formulario_insertar_peliculas.php">
                    <img src="file.png" alt="" style="border-radius:0px; cursor: pointer;">
                </a>
                <img src="imagenes/perfil/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>" alt="Perfil" id="abrirModal3" style="cursor: pointer;">
                <a href="logout.php"><button class="login-button">Cerrar sesión</button></a>
            </div>
            <?php
        } else {
            ?>
            <div>
                <button class="login-button" id="abrirModal">Iniciar sesión</button>
                <button class="login-button" id="abrirModal2">Registrarse</button>
            </div>
            <?php
        }
        ?>
    </header>

    <div class="categoria">
        <nav>
            <?php
            while($genero = $result_generos->fetch_assoc()) {
                echo "<a href='index.php?genero=" . $genero['pk_genero'] . "'>" . 
                     htmlspecialchars($genero['nombre_genero']) . "</a>";
            }
            ?>
        </nav>
    </div>

    <main>
        <section class="detalle">
            <div class="imagen">
                <img src="imagenes/<?php echo htmlspecialchars($pelicula['foto']); ?>" 
                     alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
            </div>
            <div class="informacion">
                <h2><?php echo htmlspecialchars($pelicula['titulo']); ?></h2>
                <div class="genero">
                    <div class="genero-btn">
                        <?php echo htmlspecialchars($pelicula['nombre_genero']); ?>
                    </div>
                </div>
                <p class="descripcion"><?php echo htmlspecialchars($pelicula['descripcion']); ?></p>
                <p class="fecha">Fecha de subida: <?php echo date('d/m/Y', strtotime($pelicula['fecha_subida'])); ?></p>
            </div>
        </section>

        <section class="reproductor">
            <h3>Ver Película</h3>
            <div class="video-container">
                <?php
                // Primero intentamos mostrar el video local si existe
                if (!empty($pelicula['video_local'])) {
                    $video_path = "videos/" . htmlspecialchars($pelicula['video_local']);
                    if (file_exists($video_path)) {
                        echo '<video controls width="100%" height="500" class="video-player">
                                <source src="' . $video_path . '" type="video/mp4">
                                Tu navegador no soporta la reproducción de video.
                              </video>';
                    }
                }
                // Si no hay video local o no se encuentra el archivo, intentamos con la URL
                elseif (!empty($pelicula['url'])) {
                    // Verifica si la URL es de YouTube
                    if (strpos($pelicula['url'], 'youtube.com') !== false || strpos($pelicula['url'], 'youtu.be') !== false) {
                        // Extraer el ID del video de YouTube y crear el embed
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $pelicula['url'], $matches)) {
                            $video_id = $matches[1];
                            echo '<iframe width="100%" height="500" 
                                  src="https://www.youtube.com/embed/' . $video_id . '" 
                                  frameborder="0" 
                                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                  allowfullscreen></iframe>';
                        }
                    } else {
                        // Para otras URLs de video
                        echo '<video controls width="100%" height="500" class="video-player">
                                <source src="' . htmlspecialchars($pelicula['url']) . '" type="video/mp4">
                                Tu navegador no soporta la reproducción de video.
                              </video>';
                    }
                } else {
                    echo '<p class="error-message">Lo sentimos, el video no está disponible en este momento.</p>';
                }
                ?>
            </div>
        </section>

        <style>
            .video-player {
                background-color: #000;
                max-height: 500px;
                width: 100%;
                object-fit: contain;
            }
            .error-message {
                text-align: center;
                padding: 20px;
                background-color: #f8d7da;
                color: #721c24;
                border-radius: 4px;
                margin: 10px 0;
            }
        </style>
    </main>
</body>
</html>