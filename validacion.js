// Añade un event listener para interceptar el envío del formulario con el ID 'canco'
document.getElementById('canco').addEventListener('submit', function(event) {
    // Obtiene los valores de los campos de texto del formulario (título y artista)
    let titulo = document.getElementById('titol').value;
    let artista = document.getElementById('artista').value;

    // Verifica si los campos están vacíos o contienen solo espacios en blanco
    if (titulo.trim() === '' || artista.trim() === '') {
        // Muestra un mensaje de alerta si alguno de los campos no está completo
        alert('Por favor, completa todos los campos.');
        // Cancela el envío del formulario para evitar que se procese con campos vacíos
        event.preventDefault();
    }
});
