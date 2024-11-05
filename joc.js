document.addEventListener("DOMContentLoaded", () => {
    // Obtiene los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const titulo = urlParams.get('titulo');
    const autor = urlParams.get('autor');
    const audioSrc = urlParams.get('audio');
    const archivoMovimientos = urlParams.get('archivo');
    const portadaSrc = urlParams.get('portada');

    // Actualiza el título y el autor en la página
    document.getElementById("tituloCancion").textContent = titulo;
    document.getElementById("autorCancion").textContent = autor;

    // Asigna el archivo de audio al reproductor
    const audio = document.getElementById("audioCancion");
    audio.src = audioSrc;

    // Mapa de teclas (keyCode) a símbolos visuales
    const teclaMap = {
        '37': '⬅️', 
        '38': '⬆️',  
        '39': '➡️',  
        '40': '⬇️'  
    };

    // Inicializa variables para almacenar los movimientos, estado del juego y puntuación
    let movimientos = [];
    let juegoTerminado = false;
    let movimientosCorrectos = 0;
    let totalMovimientos = 0; 
    
    // Cargar el archivo de movimientos desde la URL proporcionada
    fetch(archivoMovimientos)
        .then(response => response.text())
        .then(text => {
            // Divide el archivo de movimientos en líneas, luego en partes: tecla, tiempo de inicio y fin
            movimientos = text.split('\n')
                .map(line => line.trim().split('#')) // Separa las partes por el símbolo #
                .filter(line => line.length === 3)
                .map(line => ({
                    tecla: line[0].trim(),           // Tecla (keyCode)
                    tiempoInicio: parseFloat(line[1].trim()), // Tiempo de inicio
                    tiempoFin: parseFloat(line[2].trim()),    // Tiempo de fin
                    evaluado: false                 // Estado para saber si ya fue evaluado
                }));
            totalMovimientos = movimientos.length; // Guarda el número total de movimientos
            iniciarCuentaAtras(); // Inicia la cuenta regresiva para el juego
        })
        .catch(error => {
            console.error('Error al cargar el archivo de movimientos:', error);
            alert('No se pudo cargar el archivo de movimientos.');
        });

    let puntuacion = 0; // Inicializa la puntuación

    // Función para iniciar una cuenta regresiva antes de que empiece el juego
    function iniciarCuentaAtras() {
        const areaJuego = document.getElementById("areaJuego");
        let cuenta = 3; // Inicia en 3 segundos

        const intervaloCuentaAtras = setInterval(() => {
            if (cuenta > 0) {
                areaJuego.textContent = cuenta; // Muestra la cuenta regresiva en pantalla
                cuenta--;
            } else {
                clearInterval(intervaloCuentaAtras);
                areaJuego.textContent = '¡Go!'; // Indica el inicio del juego
                setTimeout(() => {
                    iniciarJuego(); // Comienza el juego
                }, 500);
            }
        }, 1000); // La cuenta regresiva es de 1 segundo
    }

    // Función para iniciar el juego
    function iniciarJuego() {
        if (movimientos.length === 0 || juegoTerminado) {
            alert('No hay movimientos para este juego.');
            return;
        }

        // Escucha eventos del teclado y el estado del audio
        window.addEventListener('keydown', detectarTecla);
        audio.addEventListener('pause', detenerJuegoPorPausa);
        audio.play().catch(error => console.log("Reproducción automática fallida:", error));

        // Muestra y oculta los movimientos en los tiempos indicados
        movimientos.forEach((movimiento, index) => {
            setTimeout(() => {
                mostrarMovimiento(movimiento); // Muestra el movimiento
                actualizarBarraProgreso(index + 1); // Actualiza la barra de progreso
            }, movimiento.tiempoInicio * 1000);

            setTimeout(() => ocultarMovimiento(movimiento), movimiento.tiempoFin * 1000);
        });

        // Finaliza el juego cuando el último movimiento desaparece
        const ultimoTiempoFin = Math.max(...movimientos.map(m => m.tiempoFin)) * 1000;
        setTimeout(finalizarJuego, ultimoTiempoFin + 500);
    }

    // Muestra el movimiento en el área de juego
    function mostrarMovimiento(movimiento) {
        const areaJuego = document.getElementById("areaJuego");
        const teclaSimbolo = teclaMap[movimiento.tecla] || '❓'; // Usa el símbolo de la tecla o "?" si no existe
        areaJuego.textContent = teclaSimbolo; // Muestra la flecha correspondiente
    }

    // Oculta el movimiento del área de juego
    function ocultarMovimiento(movimiento) {
        const areaJuego = document.getElementById("areaJuego");
        areaJuego.textContent = ''; // Limpia el área de juego

        // Si el movimiento no fue evaluado (no se presionó la tecla correcta a tiempo), se resta puntuación
        if (!movimiento.evaluado) {
            puntuacion -= 50;
            actualizarPuntuacion(); // Actualiza la puntuación en pantalla
        }

        movimiento.evaluado = true; // Marca el movimiento como evaluado
    }

    // Detecta la tecla presionada por el jugador
    function detectarTecla(event) {
        // Encuentra el movimiento que esté activo (dentro del tiempo válido) y que no haya sido evaluado
        const movimientoActual = movimientos.find(movimiento => 
            audio.currentTime >= movimiento.tiempoInicio && audio.currentTime <= movimiento.tiempoFin && !movimiento.evaluado
        );

        // Si hay un movimiento activo, verifica si la tecla presionada es correcta
        if (movimientoActual) {
            const teclaPresionada = event.keyCode.toString();
            
            if (teclaPresionada === movimientoActual.tecla) {
                puntuacion += 100; // Suma puntos si la tecla es correcta
                movimientosCorrectos++; // Incrementa el contador de movimientos correctos
            } else {
                puntuacion -= 50; // Resta puntos si la tecla es incorrecta
            }

            movimientoActual.evaluado = true; // Marca el movimiento como evaluado
            actualizarPuntuacion(); // Actualiza la puntuación en pantalla
            ocultarMovimiento(movimientoActual); // Oculta el movimiento
        }
    }

    // Actualiza la puntuación en pantalla
    function actualizarPuntuacion() {
        document.getElementById("puntuacion").textContent = puntuacion;
    }

    // Actualiza la barra de progreso en función de los movimientos completados
    function actualizarBarraProgreso(movimientosCompletados) {
        const barraProgreso = document.getElementById("barraProgreso");
        const progreso = (movimientosCompletados / movimientos.length) * 100;
        barraProgreso.value = progreso; // Ajusta el valor de la barra
    }

    // Detiene el juego si el audio se pausa
    function detenerJuegoPorPausa() {
        if (!juegoTerminado) {
            juegoTerminado = true;
            puntuacion = 0; // Resetea la puntuación
            alert('El juego ha sido pausado. Serás redirigido a la página principal.');
            audio.pause();
            window.location.href = 'index.html'; // Redirige a la página principal
        }
    }

    // Finaliza el juego, pausa el audio y muestra el rango obtenido
    function finalizarJuego() {
        juegoTerminado = true;
        audio.pause();
        audio.currentTime = 0; // Resetea el audio al inicio
        mostrarRango(); // Muestra el rango al final del juego
    }

    // Calcula y muestra el rango basado en los aciertos del jugador
    function mostrarRango() {
        const porcentajeAciertos = (movimientosCorrectos / totalMovimientos) * 100;
        let rango;

        // Determina el rango en función del porcentaje de aciertos
        if (porcentajeAciertos >= 90) {
            rango = "A";
        } else if (porcentajeAciertos >= 70) {
            rango = "B";
        } else if (porcentajeAciertos >= 50) {
            rango = "C";
        } else if (porcentajeAciertos >= 25) {
            rango = "D";
        } else {
            rango = "E";
        }

        // Solicita el nombre del usuario y guarda la estadística
        const nombreUsuario = prompt(`Juego terminado.\nPuntuación: ${puntuacion}\nPorcentaje de aciertos: ${porcentajeAciertos.toFixed(2)}%\nRango: ${rango}\nIntroduce tu nombre:`);
        
        if (nombreUsuario) {
            guardarEstadistica(nombreUsuario, puntuacion, rango);
            window.location.href = 'index.html'; // Redirige a la página principal
        } else {
            alert("No se ha guardado la puntuación.");
        }
    }

    // Guarda las estadísticas en una cookie
    function guardarEstadistica(nombre, puntuacion, rango) {
        const estadisticas = obtenerEstadisticas(); // Obtiene las estadísticas existentes
        estadisticas.push({ nombre, puntuacion, rango }); // Agrega la nueva estadística
        setCookie('estadisticas', JSON.stringify(estadisticas), 7); // Guarda la lista actualizada en cookies
    }

    // Obtiene las estadísticas desde la cookie
    function obtenerEstadisticas() {
        const cookieValue = getCookie('estadisticas');
        return cookieValue ? JSON.parse(cookieValue) : []; // Devuelve un array vacío si no hay cookie
    }

    // Función para guardar una cookie
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    // Función para obtener el valor de una cookie
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
