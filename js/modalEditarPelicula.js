document.addEventListener('DOMContentLoaded', () => {
    // Función para abrir el modal y rellenar los campos con los datos de la película
    function abrirModalEditar(id, titulo, categoria, foto) {
        document.getElementById('pkPelicula').value = id;
        document.getElementById('tituloPelicula').value = titulo;
        document.getElementById('categoriaPelicula').value = categoria;
        document.getElementById('modalEditarPelicula').style.display = 'flex';
    }

    // Función para cerrar el modal
    function cerrarModalEditar() {
        document.getElementById('modalEditarPelicula').style.display = 'none';
    }

    // Asignar estas funciones al objeto `window` si es necesario
    window.abrirModalEditar = abrirModalEditar;
    window.cerrarModalEditar = cerrarModalEditar;
});
