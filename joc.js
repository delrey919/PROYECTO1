async function cargarCancion() {
    try {
      const respuesta = await fetch('PHP/canciones.json');
      const datos = await respuesta.json();
      const cancion = datos.Canciones[0]; // Accedemos al primer objeto de canciones
  
      // Mostrar información de la canción
      document.getElementById('tituloCancion').innerText = cancion.titulo;
      document.getElementById('autorCancion').innerText = cancion.autor;
      document.getElementById('portadaCancion').src = cancion.portada;
  
      // Cargar el archivo de texto de la canción
      const respuestaArchivo = await fetch(cancion.archivo);
      const texto = await respuestaArchivo.text();
  
      // Procesar los datos del archivo de texto
      const movimientos = procesarArchivoTexto(texto);
  
      // Iniciar el juego con la duración de la canción (puedes definir manualmente la duración o extraerla del archivo)
      iniciarJuego(movimientos, 60); // Aquí asumimos que la duración es de 60 segundos
    } catch (error) {
      console.error('Error al cargar la canción:', error);
    }
  }
  
  cargarCancion();
  
  // Procesar el archivo de texto para extraer las teclas y los tiempos
  function procesarArchivoTexto(texto) {
    const lineas = texto.trim().split('\n');
    const numeroMovimientos = parseInt(lineas[0]);
    const movimientos = [];
  
    for (let i = 1; i <= numeroMovimientos; i++) {
      const [teclaUnicode, inicio, fin] = lineas[i].split('#').map(item => item.trim());
      movimientos.push({
        tecla: String.fromCharCode(parseInt(teclaUnicode)),
        inicio: parseFloat(inicio),
        fin: parseFloat(fin),
        presionado: false,
        mostrado: false
      });
    }
  
    return movimientos;
  }
  
  // Iniciar el juego
  function iniciarJuego(movimientos, duracionCancion) {
    let puntuacion = 0;
    const puntuacionMaxima = movimientos.length * 100;
    const tiempoInicio = Date.now();
  
    // Actualizar la barra de progreso
    function actualizarBarraProgreso() {
      const tiempoActual = (Date.now() - tiempoInicio) / 1000;
      const progreso = (tiempoActual / duracionCancion) * 100;
      document.getElementById('barraProgreso').value = progreso;
  
      if (tiempoActual < duracionCancion) {
        requestAnimationFrame(actualizarBarraProgreso);
      }
    }
  
    actualizarBarraProgreso();
  
    // Mostrar y ocultar teclas
    movimientos.forEach(movimiento => {
      setTimeout(() => {
        mostrarTecla(movimiento.tecla);
        movimiento.mostrado = true;
      }, movimiento.inicio * 1000);
  
      setTimeout(() => {
        ocultarTecla();
        movimiento.mostrado = false;
      }, movimiento.fin * 1000);
    });
  
    // Escuchar pulsaciones del teclado
    document.addEventListener('keydown', evento => {
      const teclaPresionada = evento.key.toLowerCase();
  
      movimientos.forEach(movimiento => {
        const tiempoActual = (Date.now() - tiempoInicio) / 1000;
  
        if (
          movimiento.mostrado &&
          !movimiento.presionado &&
          tiempoActual >= movimiento.inicio &&
          tiempoActual <= movimiento.fin
        ) {
          if (teclaPresionada === movimiento.tecla) {
            movimiento.presionado = true;
            puntuacion += 100;
            actualizarPuntuacion(puntuacion);
            mostrarResultado(true);
          } else {
            puntuacion -= 50;
            actualizarPuntuacion(puntuacion);
            mostrarResultado(false);
          }
        }
      });
    });
  
    // Finalizar el juego después de la duración de la canción
    setTimeout(() => {
      finalizarJuego(puntuacion, puntuacionMaxima);
    }, duracionCancion * 1000);
  }
  
  function mostrarTecla(tecla) {
    const areaJuego = document.getElementById('areaJuego');
    areaJuego.innerText = tecla;
  }
  
  function ocultarTecla() {
    const areaJuego = document.getElementById('areaJuego');
    areaJuego.innerText = '';
  }
  
  function mostrarResultado(acierto) {
    const areaJuego = document.getElementById('areaJuego');
    if (acierto) {
      areaJuego.classList.add('acierto');
      areaJuego.classList.remove('error');
    } else {
      areaJuego.classList.add('error');
      areaJuego.classList.remove('acierto');
    }
  }
  
  function actualizarPuntuacion(puntuacion) {
    document.getElementById('puntuacion').innerText = puntuacion;
  }
  
  function finalizarJuego(puntuacion, puntuacionMaxima) {
    const porcentajeAciertos = (puntuacion / puntuacionMaxima) * 100;
    let rango;
  
    if (porcentajeAciertos >= 90) rango = 'A';
    else if (porcentajeAciertos >= 70) rango = 'B';
    else if (porcentajeAciertos >= 50) rango = 'C';
    else if (porcentajeAciertos >= 25) rango = 'D';
    else rango = 'E';
  
    const nombre = prompt('La canción ha terminado.\n\nIntroduce tu nombre:');
    alert(`Nombre: ${nombre}\nPuntuación: ${puntuacion}\nRango: ${rango}`);
  }
  