<?php
include 'conexion.php';
// Consulta para obtener películas populares (puedes ajustar las condiciones de filtro)
$query_populares = "SELECT pk_pelicula, titulo, foto FROM pelicula WHERE estatus = 1 ORDER BY fecha_subida DESC LIMIT 10";
$result_populares = $conn->query($query_populares);

// Consulta para obtener películas subidas por usuarios (ajusta condiciones si lo necesitas)
$query_subidas = "SELECT pk_pelicula, titulo, foto FROM pelicula WHERE estatus = 1 AND fk_usuarios IS NOT NULL ORDER BY fecha_subida DESC LIMIT 10";
$result_subidas = $conn->query($query_subidas);

//eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
require_once 'controllers/LoginController.php';
$loginController = new LoginController();
$loginController->login();
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas</title>
    <link rel="icon" href="logo.jpg">
    <link rel="stylesheet" href="nose.css">
    <link rel="stylesheet" href="./css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php
    if (isset($error)) {
        echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
    }
    ?>
    <header class="header">
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
            <h1>Chespinema</h1>
        </div>
        <?php
        if (isset($_SESSION['nombre_usuario'])) {
            ?>
            <div class="perfil">
                <a href="formulario_insertar_peliculas.php"><img src="file.png" alt=""
                        style="border-radius:0px; cursor: pointer;"></a>
                <img src="coco.jpeg" alt="Perfil">
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"
                        style="font-size: 30px; text-decoration: none;"></i></a>
            </div>
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
                        <a href="verpelicula.html">
                            <div class="targeta">
                                <img src="imagenes/<?php echo htmlspecialchars($row['foto']); ?>" alt="Imagen"
                                    style="width: 100%; height: auto;">
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




    <!-- Modal de inicio de sesión -->
    <div class="modal-fondo" id="ventanaModal">
        <div class="modal-content">
            <div class="top-part">
                <h2>Iniciar sesión</h2>
                <span class="cerrar"><i class="fa-regular fa-circle-xmark"></i></span>
            </div>
            <span class="linea"></span>
            <form class="login-form" method="POST">
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="nombre_usuario" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="contrasena" required>

                <button type="submit" class="login-button">Iniciar sesión</button>
                <div class="register-part">
                    <p class="register-text">¿No tienes una cuenta?
                    <p class="register-link" id="abrirModal2">Regístrate</p>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de registro de usuario -->
    <div class="modal-fondo" id="ventanaModal2">
        <div class="modal-content">
            <div class="top-part">
                <h2>Registro de Usuario</h2>
                <span class="cerrar2" style="cursor: pointer;"><i class="fa-regular fa-circle-xmark"></i></span>
            </div>
            <span class="linea"></span>
            <form action="/Chespinema/registro.php" method="POST" class="login-form">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" name="nombre_usuario" required>
                <label for="correo">Correo:</label>
                <input type="email" name="correo" required>
                <label for="tel">Teléfono</label>
                <input type="text" name="tel" required>
                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" required>

                <button type="submit" class="login-button">Registrar</button>
            </form>
            <p>¿Ya tienes cuenta? <a href="">Inicia sesión</a></p>
        </div>
    </div>

    <script src="./js/modal.js"></script>
    <script src="./js/modalRegistro.js"></script>
</body>

</html>