<?php
include 'conexion.php';
// Consulta para obtener películas populares (puedes ajustar las condiciones de filtro)
$query_populares = "SELECT pk_pelicula, titulo, foto FROM pelicula WHERE estatus = 1 ORDER BY fecha_subida DESC LIMIT 10";
$result_populares = $conn->query($query_populares);

// Consulta para obtener películas subidas por usuarios (ajusta condiciones si lo necesitas)
$query_subidas = "SELECT pk_pelicula, titulo, foto FROM pelicula WHERE estatus = 1 AND fk_usuarios IS NOT NULL ORDER BY fecha_subida DESC LIMIT 10";
$result_subidas = $conn->query($query_subidas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas</title>
    <link rel="icon" href="logo.jpg">
    <link rel="stylesheet" href="nose.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
            <h1>Chespinema</h1>
        </div>
        <div class="perfil">
            <a href="formulario_insertar_peliculas.php"><img src="file.png" alt="" style="border-radius:0px; cursor: pointer;"></a>
            <img src="coco.jpeg" alt="Perfil">
        </div>
    </header>

    <div class="categoria">
        <nav>
            <a href="">Terror</a>
            <a href="">Comedia</a>
            <a href="">Acción</a>
            <a href="">Ciencia ficción</a>
        </nav>
    </div>

    <main>
        <section>
            <h2>Películas Populares</h2>
            <div class="peliculas">
                <?php while ($row = $result_populares->fetch_assoc()): ?>
                    <div class="contenedor_targeta">
                        <a href="verpelicula.php"><div class="targeta">
                        <img src="imagenes/<?php echo htmlspecialchars($row['foto']); ?>" alt="Imagen" style="width: 100%; height: auto;">
                            <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                        </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section>
            <h2>Subidas por usuarios</h2>
            <div class="peliculas">
                <?php while ($row = $result_subidas->fetch_assoc()): ?>
                    <div class="contenedor_targeta">
                        <div class="targeta">
                            <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($row['foto']) . '" alt="Imagen">' ?>
                            <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
</body>
</html>
