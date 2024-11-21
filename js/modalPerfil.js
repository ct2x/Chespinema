document.addEventListener('DOMContentLoaded', function() {
    // Ventana modal
    var modal = document.getElementById("ventanaModal3");
    var boton = document.getElementById("abrirModal3");
    var span = document.getElementsByClassName("cerrar3")[0];
    var form = document.getElementById("formEditarPerfil");

    // Cuando el usuario hace click en el botón, se abre la ventana
    boton.addEventListener("click", function() {
        modal.style.display = "flex";
    });

    // Si el usuario hace click en la x, la ventana se cierra
    span.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Si el usuario hace click fuera de la ventana, se cierra.
    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });

    // Manejar el envío del formulario
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);

        fetch('actualizar_perfil.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: data.mensaje
                }).then(() => {
                    // Cerrar el modal y actualizar los datos de sesión
                    modal.style.display = "none";
                    location.reload(); // Recargar la página para reflejar los cambios
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.mensaje
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al procesar tu solicitud.'
            });
        });
    });
});
