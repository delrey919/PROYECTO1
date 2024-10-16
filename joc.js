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
    let timeoutId;

    fetch(archivoMovimientos)
        .then(response => response.text())
        .then(text => {
            movimientos = text.split('\n')
                .map(line => line.trim().split('#')[0])
                .filter(Boolean);
            iniciarCuentaAtras();
        })
        .catch(error => {
            console.error('Error al cargar el archivo de movimientos:', error);
            alert('No se pudo cargar el archivo de movimientos.');
        });

    let indiceMovimiento = 0;
    let puntuacion = 0;
    let tiempoPorMovimiento = 1000;

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

        mostrarMovimiento();
        actualizarPuntuacion();
        actualizarBarraProgreso();

        window.addEventListener('keydown', detectarTecla);
        audio.addEventListener('pause', detenerJuegoPorPausa);
    }

    function mostrarMovimiento() {
        if (indiceMovimiento === 0) {
            setTimeout(() => {
                audio.play().catch(error => {
                    console.log("Reproducción automática fallida:", error);
                });
            }, 1000);
        }

        if (indiceMovimiento < movimientos.length && !juegoTerminado) {
            const areaJuego = document.getElementById("areaJuego");
            const movimientoActual = movimientos[indiceMovimiento].trim();

            const teclaSimbolo = teclaMap[movimientoActual] || '❓';
            areaJuego.textContent = teclaSimbolo;

            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                indiceMovimiento++;
                mostrarMovimiento();
            }, tiempoPorMovimiento);
        } else if (!juegoTerminado) {
            finalizarJuego();
        }
    }

    function detectarTecla(event) {
        const movimientoActual = movimientos[indiceMovimiento].trim();
        const teclaPresionada = event.keyCode.toString();

        if (teclaPresionada === movimientoActual) {
            puntuacion += 100;
        } else {
            puntuacion -= 50;
        }

        actualizarPuntuacion();
        clearTimeout(timeoutId);
        indiceMovimiento++;
        mostrarMovimiento();
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
        estadisticas.push({ nombre: nombre, puntuacion: puntuacion });
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
