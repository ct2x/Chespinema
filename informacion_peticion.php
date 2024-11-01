<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="informacion_peticion.css">
    <title>Document</title>
</head>

<body>
    <!-- detalle_pelicula.html -->
    <section id="detalle-validacion-pelicula" class="detalle-validacion">
        <h2>Detalles de la Petición</h2>
        <div class="detalle-contenido">
            <div class="usuario-info">
                <p><strong>Usuario:</strong> username123</p>
            </div>
            <div class="pelicula-imagen">
                <img src="ruta_imagen_pelicula.jpg" alt="Imagen de la Película">
            </div>
            <div class="pelicula-info">
                <h3>Nombre de la Película</h3>
                <p><strong>Categoría:</strong> Acción, Aventura</p>
            </div>
            <div class="pelicula-reproductor">
                <video controls>
                    <source src="ruta_pelicula.mp4" type="video/mp4">
                    Tu navegador no soporta el video.
                </video>
            </div>
            <div class="acciones-pelicula">
                <button class="btn denegar">Denegar</button>
                <button class="btn aceptar">Aceptar</button>
            </div>
        </div>
    </section>

</body>

</html>