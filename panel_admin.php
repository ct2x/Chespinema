<?php

if (session_status() == PHP_SESSION_NONE){
    session_start();

}

if(!isset($_SESSION["usuario_id"])){
    header("Location: index.php");
}

$tipo_usuario = ($_SESSION["tipo_usuario"]);

if($tipo_usuario!= "2"){
    header("Location: index.php");
}

require_once 'config/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="icon" href="logo.jpg">
    <link rel="stylesheet" href="panel_administrador.css">
    <link rel="stylesheet" href="./css/modal.css">
    <link rel="stylesheet" href="nose.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Función para confirmar la eliminación de la película
        function confirmarEliminacion(titulo) {
            return confirm(`¿Estás seguro de que quieres eliminar la película '${titulo}'?`);
        }

        // Función para mostrar la sección seleccionada
        function mostrarSeccion(seccion) {
            const secciones = document.querySelectorAll('.content-section');
            secciones.forEach((sec) => {
                sec.style.display = 'none'; // Ocultar todas las secciones
            });
            document.getElementById(seccion).style.display = 'block'; // Mostrar la sección seleccionada
        }

        // Evento para manejar el clic en los enlaces del menú
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('.sidebar a');
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevenir el comportamiento por defecto del enlace
                    mostrarSeccion(link.getAttribute('data-section')); // Mostrar la sección correspondiente
                });
            });
            // Mostrar la sección de "Lista de Usuarios" por defecto
            mostrarSeccion('lista-usuarios');
        });
    </script>
</head>
<body>
    <?php
    include('conexion.php');

    // Cambiar estatus de usuario a 0 si se ha enviado una solicitud de "banear"
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['banear_usuario'])) {
        $usuario = $_POST['banear_usuario'];
        $conn->query("UPDATE usuarios SET estatus = 0 WHERE usuario = '$usuario'");
    }

    // Cambiar estatus de película a 0 si se ha enviado una solicitud de "eliminar"
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_pelicula'])) {
        $titulo = $_POST['eliminar_pelicula'];
        $conn->query("UPDATE pelicula SET estatus = 0 WHERE titulo = '$titulo'");
    }

    // Cambiar estatus de usuario a 1 si se ha enviado una solicitud de "Quitar ban"
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quitar_ban'])) {
        $usuario = $_POST['quitar_ban'];
        $conn->query("UPDATE usuarios SET estatus = 1 WHERE usuario = '$usuario'");
    }

    //eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee

    
    ?>
    
    <header class="header">
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
            <p>Admin Panel</p>
        </div>
        <?php
        if (isset($_SESSION['nombre_usuario'])) {
            ?>
            <div class="perfil">
            <!--    <a href="formulario_insertar_peliculas.php"><img src="file.png" alt=""
                        style="border-radius:0px; cursor: pointer;"></a> !-->
                        <img src="imagenes/perfil/<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>" alt="Perfil" id="abrirModal3" style="cursor: pointer;" title="Perfil">
                <a href="logout.php"><button class="login-button">Cerrar sesión</button></a>
            </div>
        <?php }
        ?>
    </header>

    <nav class="sidebar">
        <!--<a href="#" data-section="validar-peliculas">Peticiones</a>!-->
        <a href="#" data-section="lista-usuarios">Lista de Usuarios</a>
        <a href="#" data-section="lista-negra">Lista Negra</a>
        <a href="#" data-section="lista-peliculas">Lista de Películas</a>
    </nav>

    <main>
        <!-- Validar Peticiones -->
        <section id="validar-peliculas" class="content-section" style="display: none;">
            <h2>Validar Peticiones</h2>
            <!-- Aquí iría el código para listar las peticiones -->
        </section>

        <!-- Lista de Usuarios -->
        <section id="lista-usuarios" class="content-section">
            <h2>Lista de Usuarios</h2>
            <table class="tabla-usuarios">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Fecha de Creación</th>
                        <th>Banear</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar solo usuarios activos (estatus = 1)
                    $result = $conn->query("SELECT usuario, correo, tel, fecha_creacion FROM usuarios WHERE estatus = '1'");
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['usuario'] ?></td>
                        <td><?= $row['correo'] ?></td>
                        <td><?= $row['tel'] ?></td>
                        <td><?= $row['fecha_creacion'] ?></td>
                        <td>
                            <!-- Botón para banear usuario -->
                            <form method="POST" style="display:inline;" onsubmit="return confirmarBaneo('<?= $row['usuario'] ?>');">
                                <input type="hidden" name="banear_usuario" value="<?= $row['usuario'] ?>">
                                <button type="submit" class="btn denegar">Banear</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Lista Negra -->
        <section id="lista-negra" class="content-section" style="display: none;">
            <h2>Lista Negra</h2>
            <table class="tabla-usuarios">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Fecha de Creación</th>
                        <th>Quitar ban</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Mostrar usuarios baneados
                    $result = $conn->query("SELECT usuario, correo, tel, fecha_creacion FROM usuarios WHERE estatus = 'baneado'");
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['usuario'] ?></td>
                        <td><?= $row['correo'] ?></td>
                        <td><?= $row['tel'] ?></td>
                        <td><?= $row['fecha_creacion'] ?></td>
                        <td>
                        <form method="POST" style="display:inline;" onsubmit="return confirmarBaneo('<?= $row['usuario'] ?>');">
                                <input type="hidden" name="quitar_ban" value="<?= $row['usuario'] ?>">
                                <button type="submit" class="btn denegar">Quitar ban</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Lista de Películas -->
<section id="lista-peliculas" class="content-section" style="display: none;">
    <h2>Lista de Películas</h2>
    <table class="tabla-usuarios">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT p.pk_pelicula, p.titulo, g.nombre_genero, p.foto FROM pelicula p INNER JOIN generos g ON p.fk_genero = g.pk_genero WHERE p.estatus = '1'");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><img src="imagenes/<?= $row['foto'] ?>" alt="pelicula" class="imagen-pelicula"></td>
                <td><?= $row['titulo'] ?></td>
                <td><?= $row['nombre_genero'] ?></td>
                <td>
                    <!-- Botón para editar película -->
                    <button class="btn detalles" onclick="abrirModalEditar('<?= $row['pk_pelicula'] ?>', '<?= $row['titulo'] ?>', '<?= $row['nombre_genero'] ?>', '<?= $row['foto'] ?>')">Editar</button>
                    <!-- Formulario para eliminar película -->
                    <form method="POST" style="display:inline;" onsubmit="return confirmarEliminacion('<?= $row['titulo'] ?>');">
                        <input type="hidden" name="eliminar_pelicula" value="<?= $row['titulo'] ?>">
                        <button type="submit" class="btn denegar">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

    </main>
    <?php $conn->close(); ?>

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


<!-- Modal de editar película -->
<div class="modal-fondo" id="modalEditarPelicula">
    <div class="modal-content">
        <div class="top-part">
            <h2>Editar Película</h2>
            <span class="cerrar" style="cursor: pointer;" onclick="cerrarModalEditar()"><i class="fa-regular fa-circle-xmark"></i></span>
        </div>
        <span class="linea"></span>
        <form method="POST" action="editar_pelicula.php" enctype="multipart/form-data" class="login-form">
            <input type="hidden" name="pk_pelicula" id="pkPelicula">
            
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="tituloPelicula" required>

            <label for="categoria">Categoría:</label>
            <input type="text" name="categoria" id="categoriaPelicula" required>

            <label for="foto">Foto:</label>
            <input type="file" name="foto" id="fotoPelicula" accept="image/*">

            <button type="submit" class="login-button">Guardar cambios</button>
        </form>
    </div>
</div>


    <script src="./js/modalPerfil.js"></script>
    <script src="./js/modalEditarPelicula.js"></script>
    <script src="admin.js"></script>

    

<?php


if (isset($_SESSION['pelicula_success'])) {
    echo "<script>
            Swal.fire({
                icon: 'success',
                title: '¡Película actualizada!',
                text: 'La película se ha editado exitosamente.',
                confirmButtonText: 'Aceptar'
            }).then(() => {
                window.location.href = 'panel_admin.php'; // Redireccionar al panel de administrador
            });
        </script>";
    unset($_SESSION['pelicula_success']);
}
?>
</body>
</html>