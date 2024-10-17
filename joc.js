document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const titulo = urlParams.get('titulo');
    const autor = urlParams.get('autor');
    const audioSrc = urlParams.get('audio');
    const archivoMovimientos = urlParams.get('archivo');
    const portadaSrc = urlParams.get('portada');

    document.getElementById("tituloCancion").textContent = titulo;
    document.getElementById("autorCancion").textContent = autor;

    const audio = document.getElementById("audioCancion");
    audio.src = audioSrc;

    const teclaMap = {
        '37': '⬅️',
        '38': '⬆️',
        '39': '➡️',
        '40': '⬇️'
    };

    let movimientos = [];
    let juegoTerminado = false;

    fetch(archivoMovimientos)
        .then(response => response.text())
        .then(text => {
            movimientos = text.split('\n')
                .map(line => line.trim().split('#'))
                .filter(line => line.length === 3)
                .map(line => ({
                    tecla: line[0].trim(),
                    tiempoInicio: parseFloat(line[1].trim()),
                    tiempoFin: parseFloat(line[2].trim()),
                    evaluado: false // Estado adicional para saber si ya fue evaluado
                }));
            iniciarCuentaAtras();
        })
        .catch(error => {
            console.error('Error al cargar el archivo de movimientos:', error);
            alert('No se pudo cargar el archivo de movimientos.');
        });

    let puntuacion = 0;

    function iniciarCuentaAtras() {
        const areaJuego = document.getElementById("areaJuego");
        let cuenta = 3;

        const intervaloCuentaAtras = setInterval(() => {
            if (cuenta > 0) {
                areaJuego.textContent = cuenta;
                cuenta--;
            } else {
                clearInterval(intervaloCuentaAtras);
                areaJuego.textContent = '¡Go!';
                setTimeout(() => {
                    iniciarJuego();
                }, 500);
            }
        }, 1000);
    }

    function iniciarJuego() {
        if (movimientos.length === 0 || juegoTerminado) {
            alert('No hay movimientos para este juego.');
            return;
        }

        window.addEventListener('keydown', detectarTecla);
        audio.addEventListener('pause', detenerJuegoPorPausa);
        audio.play().catch(error => console.log("Reproducción automática fallida:", error));

        // Mostrar y ocultar movimientos en los tiempos indicados
        movimientos.forEach((movimiento, index) => {
            setTimeout(() => {
                mostrarMovimiento(movimiento);
                actualizarBarraProgreso(index + 1); // Actualizar progreso cada vez que aparece un movimiento
            }, movimiento.tiempoInicio * 1000);

            setTimeout(() => ocultarMovimiento(movimiento), movimiento.tiempoFin * 1000);
        });

        // Finalizar el juego cuando el último movimiento haya desaparecido
        const ultimoTiempoFin = Math.max(...movimientos.map(m => m.tiempoFin)) * 1000;
        setTimeout(finalizarJuego, ultimoTiempoFin + 500);
    }

    function mostrarMovimiento(movimiento) {
        const areaJuego = document.getElementById("areaJuego");
        const teclaSimbolo = teclaMap[movimiento.tecla] || '❓';
        areaJuego.textContent = teclaSimbolo;
    }

    function ocultarMovimiento(movimiento) {
        const areaJuego = document.getElementById("areaJuego");
        areaJuego.textContent = '';
        movimiento.evaluado = true; // Marcar como evaluado cuando desaparezca
    }

    function detectarTecla(event) {
        const movimientoActual = movimientos.find(movimiento => 
            audio.currentTime >= movimiento.tiempoInicio && audio.currentTime <= movimiento.tiempoFin && !movimiento.evaluado
        );

        if (movimientoActual) {
            const teclaPresionada = event.keyCode.toString();
            
            if (teclaPresionada === movimientoActual.tecla) {
                puntuacion += 100;
            } else {
                puntuacion -= 50;
            }

            movimientoActual.evaluado = true; // Marcar el movimiento como evaluado después de la primera pulsación
            actualizarPuntuacion();
            // Ocultar movimiento inmediatamente después de presionar la tecla
            ocultarMovimiento(movimientoActual);
        }
    }

    function actualizarPuntuacion() {
        document.getElementById("puntuacion").textContent = puntuacion;
    }

    function actualizarBarraProgreso(movimientosCompletados) {
        const barraProgreso = document.getElementById("barraProgreso");
        const progreso = (movimientosCompletados / movimientos.length) * 100;
        barraProgreso.value = progreso;
    }

    function detenerJuegoPorPausa() {
        if (!juegoTerminado) {
            juegoTerminado = true;
            puntuacion = 0;
            alert('El juego ha sido pausado. Serás redirigido a la página principal.');
            audio.pause();

            window.location.href = 'inicio.html';
        }
    }

    function finalizarJuego() {
        juegoTerminado = true;
        audio.pause();
        audio.currentTime = 0;
        pedirNombreUsuario();
    }

    function pedirNombreUsuario() {
        const nombreUsuario = prompt("Juego terminado.\nPuntuación: " + puntuacion + "\nIntroduce tu nombre:");
        if (nombreUsuario) {
            guardarEstadistica(nombreUsuario, puntuacion);
            window.location.href = 'inicio.html'; // Redirigir al usuario a la página principal
        } else {
            alert("No se ha guardado la puntuación.");
        }
    }

    function guardarEstadistica(nombre, puntuacion) {
        const estadisticas = obtenerEstadisticas(); // Obtener estadísticas existentes
        estadisticas.push({ nombre, puntuacion });
        setCookie('estadisticas', JSON.stringify(estadisticas), 7); // Guardar la lista actualizada
    }

    function obtenerEstadisticas() {
        const cookieValue = getCookie('estadisticas');
        return cookieValue ? JSON.parse(cookieValue) : [];
    }

    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});
