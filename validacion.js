document.getElementById('canco').addEventListener('submit', function(event) {
    let titulo = document.getElementById('titol').value;
    let artista = document.getElementById('artista').value;

    if (titulo.trim() === '' || artista.trim() === '') {
        alert('Por favor, completa todos los campos.');
        event.preventDefault();
    }
});
