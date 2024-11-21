function validarArchivo() {
    const video = document.getElementById('video');
    const maxSize = 500 * 1024 * 1024; // 500MB en bytes
    
    if (!video.files || !video.files[0]) return true;
    
    if (video.files[0].size > maxSize) {
        alert('El archivo es demasiado grande. El tamaño máximo es 500MB.');
        video.value = '';
        return false;
    }
    
    const sizeMB = Math.round(video.files[0].size / (1024 * 1024) * 100) / 100;
    document.getElementById('fileSize').textContent = `Tamaño del archivo: ${sizeMB}MB`;
    
    return true;
}

function subirPelicula(form) {
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();

    // Mostrar barra de carga
    document.getElementById('barraCarga').style.display = 'block';

    xhr.open('POST', form.action, true);
    
    // Actualizar la barra de progreso
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const porcentaje = (e.loaded / e.total) * 100;
            document.getElementById('progreso').style.width = porcentaje + '%';
            document.getElementById('porcentaje').textContent = Math.round(porcentaje) + '%';
        }
    };

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Procesar la respuesta, mostrar mensaje de éxito
            alert("¡Película subida con éxito!");
            window.location.href = "index.php"; // Redirigir o actualizar la página
        } else {
            alert("Hubo un error al subir la película.");
        }
        // Ocultar barra de carga después de la carga
        document.getElementById('barraCarga').style.display = 'none';
    };

    xhr.send(formData);
    return false; // Evitar que el formulario se envíe de manera tradicional
}