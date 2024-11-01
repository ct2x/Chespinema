<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Película</title>
    <link rel="icon" href="logo.jpg">
    <link rel="stylesheet" href="ver_pelicula.css">
</head>
<body>
    <header class="header">
        <a href="nose.php" style="text-decoration: none; color: white;"><div class="logo">
            <img src="logo.jpg" alt="Logo">
            <h1>Chespinema</h1>
        </div></a>
        <div class="perfil">
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
        <section class="detalle">
            <div class="imagen">
                <img src="karatekid.jpeg" alt="Imagen de la película">
            </div>
            <div class="informacion">
                <h2>The Karate Kid</h2>
                <div class="genero">
                    <div class="genero-btn">terror</div>
                    <div class="genero-btn">terror</div>
                </div>
                <p class="descripcion">esta es una pelicula bien epica porque es de karate y nose quemas aunque ni es de karate es mas de kung fu en la pelicula lo mencionana a cada rato.</p>
            </div>
        </section>

        <section class="reproductor">
            <h3>Ver Película</h3>
            <video controls width="100%" height="auto">
                <source src="pelicula.mp4" type="video/mp4">
                Tu navegador no soporta la reproducción de video.
            </video>
        </section>
    </main>
</body>
</html>
