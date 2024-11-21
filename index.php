<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'conexion.php';

// Pagination setup
$results_per_page = 30; // Number of movies per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page, default to 1
$page = max(1, $page); // Ensure page is at least 1

// Calculate offset
$offset = ($page - 1) * $results_per_page;

// Total movies count
$total_movies_query = "SELECT COUNT(*) AS total FROM pelicula WHERE estatus = 1";
$total_movies_result = $conn->query($total_movies_query);
$total_movies = $total_movies_result->fetch_assoc()['total'];

// Calculate total pages
$total_pages = ceil($total_movies / $results_per_page);

// Modify the popular movies query to include LIMIT and OFFSET
$query_populares = "SELECT pk_pelicula, titulo, foto FROM pelicula WHERE estatus = 1 ORDER BY fecha_subida DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query_populares);
$stmt->bind_param("ii", $results_per_page, $offset);
$stmt->execute();
$result_populares = $stmt->get_result();

require_once 'controllers/LoginController.php';
$loginController = new LoginController();
$loginController->login();

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
    <link rel="stylesheet" href="./css/buscador.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <a href="formulario_insertar_peliculas.php"><img src="file.png" alt=""
                        style="border-radius:0px; cursor: pointer;" alt="Subir peliculas" title="Subir peliculas"></a>
                <img src="imagenes/perfil/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>" alt="Perfil" id="abrirModal3" style="cursor: pointer;" title="Perfil">
                <a href="logout.php"><button class="login-button">Cerrar sesión</button></a>
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
            <?php
            // Consulta para obtener los géneros
            $query_generos = "SELECT pk_genero, nombre_genero FROM generos";
            $result_generos = $conn->query($query_generos);

            while ($genero = $result_generos->fetch_assoc()) {
                echo "<a href='#' data-genero='" . $genero['pk_genero'] . "'>" . htmlspecialchars($genero['nombre_genero']) . "</a>";
            }
            ?>
        </nav>
    </div>

    <main>
        <section>
            <div class="buscador">
                <input type="text" id="searchInput" placeholder="Buscar películas...">
            </div>
            <h2>Películas recientes</h2>
            <div class="peliculas">
                <?php while ($row = $result_populares->fetch_assoc()): ?>
                    <div class="contenedor_targeta">
                        <a href="verpelicula.php?id=<?php echo $row['pk_pelicula']; ?>">
                            <div class="targeta">
                                <img src="imagenes/<?php echo htmlspecialchars($row['foto']); ?>" alt="Imagen"
                                    style="width: 100%; height: auto;">
                                <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination Navigation -->
            <div class="pagination">
                <?php
                // Previous page link
                if ($page > 1) {
                    echo "<a href='?page=" . ($page - 1) . "'>Anterior</a>";
                } else {
                    echo "<span class='disabled'>Anterior</span>";
                }

                // Page numbers
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo "<span class='current'>$i</span>";
                    } else {
                        echo "<a href='?page=$i'>$i</a>";
                    }
                }

                // Next page link
                if ($page < $total_pages) {
                    echo "<a href='?page=" . ($page + 1) . "'>Siguiente</a>";
                } else {
                    echo "<span class='disabled'>Siguiente</span>";
                }
                ?>
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
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="nombre_usuario" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="contrasena" required>

                <button type="submit" class="login-button">Iniciar sesión</button>
                <div class="register-part">
                    <!--<p class="register-text">¿No tienes una cuenta?
                    <p class="register-link" id="abrirModal2">Regístrate</p>
                    </p> !-->
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
            <form action="registro.php" method="POST" class="login-form">
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
            <!--<p>¿Ya tienes cuenta? <a href="">Inicia sesión</a></p>!-->
        </div>
    </div>

    <!-- Modal de editar perfil -->
    <div class="modal-fondo" id="ventanaModal3">
        <div class="modal-content">
            <div class="top-part">
                <h2>Editar perfil</h2>
                <span class="cerrar3" style="cursor: pointer;"><i class="fa-regular fa-circle-xmark"></i></span>
            </div>
            <span class="linea"></span>
            <form id="formEditarPerfil" class="login-form" method="POST" enctype="multipart/form-data">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" name="nombre_usuario" required
                    value="<?php echo isset($_SESSION['nombre_usuario']) ? htmlspecialchars($_SESSION['nombre_usuario']) : ''; ?>">

                <label for="correo">Correo:</label>
                <input type="email" name="correo" required
                    value="<?php echo isset($_SESSION['correo']) ? htmlspecialchars($_SESSION['correo']) : ''; ?>">

                <label for="tel">Teléfono</label>
                <input type="text" name="tel" required
                    value="<?php echo isset($_SESSION['tel']) ? htmlspecialchars($_SESSION['tel']) : ''; ?>">

                <label for="foto">Foto de perfil:</label>
                <input type="file" name="foto" accept="image/*" >

                <?php if (isset($_SESSION['foto_perfil']) && !empty($_SESSION['foto_perfil'])): ?>
                    <img src="imagenes/perfil/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>" alt="Foto actual" style="width: 100px; height: 100px; object-fit: cover; margin: 10px 0;">
                <?php endif; ?>

                <button type="submit" class="login-button">Guardar cambios</button>
            </form>
        </div>
    </div>

    <script src="./js/modal.js"></script>
    <script src="./js/modalRegistro.js"></script>
    <script src="./js/buscadorMasGeneros.js"></script>
    <script src="./js/modalPerfil.js"></script>

    <?php
    // print_r($_SESSION); // Para depurar si las variables de sesión están presentes

    if (isset($_SESSION['login_success'])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: 'Has iniciado sesión correctamente',
            timer: 2000,
            showConfirmButton: false
        });
    </script>";
        unset($_SESSION['login_success']);
    }

    if (isset($_SESSION['login_error'])) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Usuario o contraseña incorrectos'
        });
    </script>";
        unset($_SESSION['login_error']);
    }

    // Alertas para registro
    if (isset($_SESSION['registro_success'])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: '¡Registro exitoso!',
            text: 'Tu cuenta ha sido creada correctamente',
            timer: 2000,
            showConfirmButton: false
        });
    </script>";
        unset($_SESSION['registro_success']);
    }

    if (isset($_SESSION['registro_error'])) {
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de usuario o correo ya está en uso'
        });
    </script>";
        unset($_SESSION['registro_error']);
    }

    // Alerta para logout
    if (isset($_SESSION['logout_success'])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: '¡Hasta pronto!',
            text: 'Has cerrado sesión correctamente',
            timer: 2000,
            showConfirmButton: false
        });
    </script>";
        unset($_SESSION['logout_success']);
    }

    ?>
</body>

</html>