document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const titulo = urlParams.get('titulo');
    const autor = urlParams.get('autor');
    const audioSrc = urlParams.get('audio');
    const archivoMovimientos = urlParams.get('archivo');
    const portadaSrc = urlParams.get('portada');

    // Mostrar información de la canción
    document.getElementById("tituloCancion").textContent = titulo;
    document.getElementById("autorCancion").textContent = autor;
    document.getElementById("portadaCancion").src = portadaSrc;

    // Mapeo de códigos numéricos a flechas de dirección
    const teclaMap = {
        '37': '⬅️', // Flecha izquierda
        '38': '⬆️', // Flecha arriba
        '39': '➡️', // Flecha derecha
        '40': '⬇️'  // Flecha abajo
    };

    let movimientos = [];
    fetch(archivoMovimientos)
        .then(response => response.text())
        .then(text => {
            // Aquí se parsea el archivo de movimientos, ignorando los tiempos
            movimientos = text.split('\n')
                .map(line => line.trim().split('#')[0]) // Tomar solo los códigos de teclas, ignorar tiempos
                .filter(Boolean); // Eliminar líneas vacías
            movimientosTotales = movimientos.length;
            iniciarJuego();
        })
        .catch(error => {
            console.error('Error al cargar el archivo de movimientos:', error);
            alert('No se pudo cargar el archivo de movimientos.');
        });

    let indiceMovimiento = 0;
    let puntuacion = 0;
    let aciertos = 0;
    let errores = 0;
    let tiempoPorMovimiento = 0; // Tiempo de duración entre teclas
    let tiempoTranscurrido = 0; // Tiempo transcurrido para sincronizar con la barra de progreso

    function iniciarJuego() {
        if (movimientos.length === 0) {
            alert('No hay movimientos para este juego.');
            return;
        }

        tiempoPorMovimiento = 2.25 / movimientos.length; // Divide la duración total por el número de movimientos
        mostrarMovimiento();
        actualizarPuntuacion();
        actualizarBarraProgreso();
    }

    function mostrarMovimiento() {
        if (indiceMovimiento < movimientos.length) {
            const areaJuego = document.getElementById("areaJuego");
            const movimientoActual = movimientos[indiceMovimiento].trim();

            // Mostrar la tecla correspondiente (flecha) según el código de tecla
            const teclaSimbolo = teclaMap[movimientoActual] || '❓'; // '❓' si no se encuentra la tecla
            areaJuego.textContent = teclaSimbolo;

            // Esperar a que el usuario presione una tecla
            window.addEventListener('keydown', detectarTecla);
        } else {
            finalizarJuego();
        }
    }

    function detectarTecla(event) {
        window.removeEventListener('keydown', detectarTecla);

        const movimientoActual = movimientos[indiceMovimiento].trim();
        const teclaPresionada = event.keyCode; // Capturar la tecla presionada (código numérico)

        if (teclaPresionada == movimientoActual) {
            puntuacion += 100;
            aciertos++;
        } else {
            puntuacion -= 50;
            errores++;
        }

        indiceMovimiento++;
        actualizarPuntuacion();
        mostrarMovimiento();
    }

    function actualizarPuntuacion() {
        document.getElementById("puntuacion").textContent = puntuacion;
    }

    function actualizarBarraProgreso() {
        const barraProgreso = document.getElementById("barraProgreso");

        // Actualizar la barra según el número de movimientos
        tiempoTranscurrido += tiempoPorMovimiento;

        if (indiceMovimiento < movimientos.length) {
            barraProgreso.value = (indiceMovimiento / movimientos.length) * 100;
            setTimeout(actualizarBarraProgreso, tiempoPorMovimiento * 1000); // Actualiza la barra según el tiempo por movimiento
        }
    }

    function finalizarJuego() {
        alert(`Juego terminado.\nPuntuación: ${puntuacion}`);
    }
});
