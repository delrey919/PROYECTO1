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

    // Cargar y reproducir automáticamente el audio
    const audio = document.getElementById("audioCancion");
    audio.src = audioSrc;

    // Asegurarse de que el audio comience automáticamente
    audio.play().catch(error => {
        console.log("Reproducción automática fallida:", error);
    });

    // Mapeo de códigos numéricos a flechas de dirección
    const teclaMap = {
        '37': '⬅️', // Flecha izquierda
        '38': '⬆️', // Flecha arriba
        '39': '➡️', // Flecha derecha
        '40': '⬇️'  // Flecha abajo
    };

    let movimientos = [];
    let juegoTerminado = false;
    let timeoutId;

    fetch(archivoMovimientos)
        .then(response => response.text())
        .then(text => {
            movimientos = text.split('\n')
                .map(line => line.trim().split('#')[0]) // Tomar solo los códigos de teclas, ignorar tiempos
                .filter(Boolean); // Eliminar líneas vacías
            iniciarJuego();
        })
        .catch(error => {
            console.error('Error al cargar el archivo de movimientos:', error);
            alert('No se pudo cargar el archivo de movimientos.');
        });

    let indiceMovimiento = 0;
    let puntuacion = 0;
    let tiempoPorMovimiento = 1000; // Tiempo en milisegundos que cada tecla estará en pantalla

    function iniciarJuego() {
        if (movimientos.length === 0 || juegoTerminado) {
            alert('No hay movimientos para este juego.');
            return;
        }

        mostrarMovimiento();
        actualizarPuntuacion();
        actualizarBarraProgreso();

        // Detectar cuando el jugador presiona una tecla
        window.addEventListener('keydown', detectarTecla);

        // Detectar si el audio se pausa
        audio.addEventListener('pause', detenerJuegoPorPausa);
    }

    function mostrarMovimiento() {
        if (indiceMovimiento < movimientos.length && !juegoTerminado) {
            const areaJuego = document.getElementById("areaJuego");
            const movimientoActual = movimientos[indiceMovimiento].trim();

            // Mostrar la tecla correspondiente (flecha) según el código de tecla
            const teclaSimbolo = teclaMap[movimientoActual] || '❓'; // '❓' si no se encuentra la tecla
            areaJuego.textContent = teclaSimbolo;

            clearTimeout(timeoutId); // Limpiar cualquier timeout anterior

            timeoutId = setTimeout(() => {
                indiceMovimiento++;
                mostrarMovimiento(); // Mostrar el siguiente movimiento
            }, tiempoPorMovimiento);
        } else if (!juegoTerminado) {
            finalizarJuego();
        }
    }

    function detectarTecla(event) {
        const movimientoActual = movimientos[indiceMovimiento].trim(); // La tecla actual en pantalla
        const teclaPresionada = event.keyCode.toString(); // Captura la tecla presionada en código numérico

        if (teclaPresionada === movimientoActual) {
            puntuacion += 100; // Sumar puntos
        } else {
            puntuacion -= 50; // Restar puntos si se equivoca
        }

        actualizarPuntuacion();
        clearTimeout(timeoutId); // Cancelar el timeout automático y avanzar manualmente
        indiceMovimiento++;
        mostrarMovimiento(); // Mostrar el siguiente movimiento
    }

    function actualizarPuntuacion() {
        document.getElementById("puntuacion").textContent = puntuacion;
    }

    function actualizarBarraProgreso() {
        const barraProgreso = document.getElementById("barraProgreso");
        const progresoInterval = setInterval(() => {
            const progreso = (indiceMovimiento / movimientos.length) * 100;
            barraProgreso.value = progreso;

            if (indiceMovimiento >= movimientos.length || juegoTerminado) {
                clearInterval(progresoInterval);
            }
        }, tiempoPorMovimiento);
    }

    // Función para detener el juego si se pausa la canción
    function detenerJuegoPorPausa() {
        if (!juegoTerminado) {
            juegoTerminado = true;
            puntuacion = 0; // Asignar puntuación de 0
            alert('El juego ha sido pausado. Serás redirigido a la página principal.');
            audio.pause(); // Asegurarse de que el audio esté detenido

            // Redirigir a la página principal después de la alerta
            window.location.href = 'inicio.html'; // Redirige a la página principal
        }
    }

    function finalizarJuego() {
        juegoTerminado = true;
        alert(`Juego terminado.\nPuntuación: ${puntuacion}`);
        audio.pause(); // Pausar el audio al final del juego
    }
});
