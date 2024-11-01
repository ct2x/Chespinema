// Selecciona todos los enlaces del sidebar y las secciones de contenido
const sidebarLinks = document.querySelectorAll('.sidebar a');
const sections = document.querySelectorAll('.content-section');

// Función para mostrar la sección seleccionada y ocultar las demás
function showSection(sectionId) {
    sections.forEach(section => {
        section.style.display = section.id === sectionId ? 'block' : 'none';
    });
}

// Función para activar el enlace seleccionado
function setActiveLink(selectedLink) {
    sidebarLinks.forEach(link => {
        link.classList.remove('active');  // Remueve la clase active de todos los enlaces
    });
    selectedLink.classList.add('active');  // Agrega la clase active al enlace seleccionado
}

// Añade un evento click a cada enlace del sidebar
sidebarLinks.forEach(link => {
    link.addEventListener('click', (event) => {
        event.preventDefault();  // Evita el comportamiento predeterminado del enlace
        const sectionId = link.getAttribute('data-section');  // Obtiene el id de la sección a mostrar
        showSection(sectionId);  // Llama a la función para mostrar la sección
        setActiveLink(link);  // Llama a la función para marcar el enlace activo
    });
});
