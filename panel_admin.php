<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="panel_administrador.css">
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
            <Admin>Admin Panel</p>
        </div>
        <div class="perfil">
            <img src="coco.jpeg" alt="Perfil Admin">
        </div>
    </header>

    <nav class="sidebar">
        <a href="#" data-section="validar-peliculas">Peticiones</a>
        <a href="#" data-section="lista-usuarios">Lista de Usuarios</a>
        <a href="#" data-section="lista-peliculas">Lista de Películas</a>
    </nav>

    <main>
        <section id="validar-peliculas" class="content-section">
            <h2>Validar Peticiones</h2>
            <div class="tira-pelicula">
                <div class="info-pelicula">
                    <h3 class="nombre-pelicula">KARATE KID</h3>
                    <p class="nombre-usuario">Usuario: PEPITO</p>
                </div>
                <div class="acciones-pelicula">
                    <button class="btn detalles" onclick="mostrarDetalles()">Detalles</button>
                    <button class="btn denegar">Denegar</button>
                    <button class="btn aceptar">Aceptar</button>
                </div>
            </div>
            <div class="tira-pelicula">
                <div class="info-pelicula">
                    <h3 class="nombre-pelicula">KARATE KID</h3>
                    <p class="nombre-usuario">Usuario: PEPITO</p>
                </div>
                <div class="acciones-pelicula">
                    <button class="btn detalles" onclick="mostrarDetalles()">Detalles</button>
                    <button class="btn denegar">Denegar</button>
                    <button class="btn aceptar">Aceptar</button>
                </div>
            </div>
            <div class="tira-pelicula">
                <div class="info-pelicula">
                    <h3 class="nombre-pelicula">KARATE KID</h3>
                    <p class="nombre-usuario">Usuario: PEPITO</p>
                </div>
                <div class="acciones-pelicula">
                    <button class="btn detalles" onclick="mostrarDetalles()">Detalles</button>
                    <button class="btn denegar">Denegar</button>
                    <button class="btn aceptar">Aceptar</button>
                </div>
            </div>
            <div class="tira-pelicula">
                <div class="info-pelicula">
                    <h3 class="nombre-pelicula">KARATE KID</h3>
                    <p class="nombre-usuario">Usuario: PEPITO</p>
                </div>
                <div class="acciones-pelicula">
                    <button class="btn detalles" onclick="mostrarDetalles()">Detalles</button>
                    <button class="btn denegar">Denegar</button>
                    <button class="btn aceptar">Aceptar</button>
                </div>
            </div>
        </section>




        <section id="lista-usuarios" class="content-section" style="display: none;">
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
                    <tr>
                        <td>Pepito</td>
                        <td>pepito@gmail.com</td>
                        <td>32456789</td>
                        <td>31/10/2024</td>
                        <td><button class="btn denegar">Banear</button></td>
                    </tr>
                    <tr>
                        <td>Pepito</td>
                        <td>pepito@gmail.com</td>
                        <td>32456789</td>
                        <td>31/10/2024</td>
                        <td><button class="btn denegar">Banear</button></td>
                    </tr>
                    <tr>
                        <td>Pepito</td>
                        <td>pepito@gmail.com</td>
                        <td>32456789</td>
                        <td>31/10/2024</td>
                        <td><button class="btn denegar">Banear</button></td>
                    </tr>


                </tbody>
            </table>
        </section>

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
                    <tr>
                        <td><img src="coco.jpeg" alt="pelicula" class="imagen-pelicula"></td>
                        <td>EL coco de los zapateros</td>
                        <td>Terror</td>
                        <td>
                            <button class="btn detalles">Editar</button>
                            <button class="btn denegar">Eliminar</button>
                        </td>
                    </tr>
                    <tr>
                        <td><img src="file.png" alt="pelicula" class="imagen-pelicula"></td>
                        <td>The karate Kid</td>
                        <td>Terror</td>
                        <td>
                            <button class="btn detalles">Editar</button>
                            <button class="btn denegar">Eliminar</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </section>
    </main>




    <script src="admin.js"></script>
    
</body>

</html>