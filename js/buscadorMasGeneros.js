document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryLinks = document.querySelectorAll('.categoria nav a');
    let timeoutId = null;

    // Función para realizar la búsqueda
    function realizarBusqueda() {
        const searchTerm = searchInput.value.trim();
        
        fetch(`buscar_peliculas.php?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                actualizarResultados(data);
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para filtrar por género
    function filtrarPorGenero(generoId) {
        fetch(`filtrar_por_genero.php?genero=${generoId}`)
            .then(response => response.json())
            .then(data => {
                actualizarResultados(data);
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para actualizar los resultados en el DOM
    function actualizarResultados(peliculas) {
        const contenedorPeliculas = document.querySelector('.peliculas');
        contenedorPeliculas.innerHTML = ''; // Limpiar resultados anteriores

        peliculas.forEach(pelicula => {
            const peliculaHTML = `
                <div class="contenedor_targeta">
                    <a href="verpelicula.php?id=${pelicula.pk_pelicula}">
                        <div class="targeta">
                            <img src="imagenes/${pelicula.foto}" alt="${pelicula.titulo}" style="width: 100%; height: auto;">
                            <h3>${pelicula.titulo}</h3>
                        </div>
                    </a>
                </div>
            `;
            contenedorPeliculas.innerHTML += peliculaHTML;
        });

        // Si no hay resultados, mostrar un mensaje
        if (peliculas.length === 0) {
            contenedorPeliculas.innerHTML = '<p style="text-align: center; width: 100%;">No se encontraron películas</p>';
        }
    }

    // Evento input con debounce para la búsqueda
    searchInput.addEventListener('input', function() {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(realizarBusqueda, 300);
    });

    // Eventos para los enlaces de categorías
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            // Remover clase activa de todos los enlaces
            categoryLinks.forEach(l => l.classList.remove('active'));
            // Añadir clase activa al enlace clickeado
            this.classList.add('active');
            
            const generoId = this.getAttribute('data-genero');
            filtrarPorGenero(generoId);
        });
    });
});