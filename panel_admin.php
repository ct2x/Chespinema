<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="panel_administrador.css">
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
    ?>
    
    <header class="header">
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
            <p>Admin Panel</p>
        </div>
        <div class="perfil">
            <img src="coco.jpeg" alt="Perfil Admin">
        </div>
    </header>

    <nav class="sidebar">
        <a href="#" data-section="validar-peliculas">Peticiones</a>
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
                    $result = $conn->query("SELECT p.titulo, g.nombre_genero, p.foto FROM pelicula p INNER JOIN generos g ON p.fk_genero = g.pk_genero WHERE p.estatus = '1'");
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><img src="imagenes/<?= $row['foto'] ?>" alt="pelicula" class="imagen-pelicula"></td>
                        <td><?= $row['titulo'] ?></td>
                        <td><?= $row['nombre_genero'] ?></td>
                        <td>
                            <button class="btn detalles">Editar</button>
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
</body>
<script src="admin.js"></script>
</html>
