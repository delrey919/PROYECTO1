// Función para obtener parámetros de la URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Asignar datos de la canción a la interfaz
const tituloCancion = getQueryParam('titulo');
const autorCancion = getQueryParam('autor');
const audioCancion = getQueryParam('audio');
const portadaCancion = getQueryParam('portada');
const archivoTeclas = getQueryParam('archivo');

document.getElementById('tituloCancion').innerText = tituloCancion;
document.getElementById('autorCancion').innerText = autorCancion;
document.getElementById('audioCancion').src = audioCancion;
document.getElementById('portadaCancion').src = portadaCancion;

// Variables del juego
let puntuacion = 0;
let teclas = [];
let progreso = 0;
let totalTeclas = 0;

// Cargar archivo de teclas
fetch(archivoTeclas)
    .then(response => response.text())
    .then(data => {
        teclas = data.split('\n').map(linea => {
            const [tecla, inicio, fin] = linea.split('#').map(val => val.trim());
            return { tecla, inicio: parseFloat(inicio), fin: parseFloat(fin) };
        });
        totalTeclas = teclas.length;
        iniciarJuego();
    })
    .catch(error => {
        console.error('Error al cargar el archivo de teclas:', error);
    });

// Función para iniciar el juego
function iniciarJuego() {
    let indiceTecla = 0;

    // Registrar eventos de teclado
    document.addEventListener('keydown', (event) => {
        const teclaPresionada = event.keyCode;

        if (indiceTecla < teclas.length && teclaPresionada === parseInt(teclas[indiceTecla].tecla)) {
            puntuacion++;
            indiceTecla++;
            actualizarProgreso(indiceTecla);
            document.getElementById('puntuacion').innerText = puntuacion;

            if (indiceTecla === totalTeclas) {
                finalizarJuego();
            }
        }
    });

    // Aquí eliminamos el renderizado de las teclas en pantalla
}

// Actualizar barra de progreso
function actualizarProgreso(indiceActual) {
    progreso = (indiceActual / totalTeclas) * 100;
    document.getElementById('barraProgreso').value = progreso;
}

// Finalizar juego
function finalizarJuego() {
    alert('¡Juego completado! Puntuación: ' + puntuacion);
}
