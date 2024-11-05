<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar cançó</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="menu-button">
        <a href="index.html">TORNAR</a>
    </div>

    <div class="container">
        <div class="title">
            <h1>Modificar cançó</h1>
        </div>

        <!-- Código PHP para cargar el archivo JSON con las canciones -->
        <?php
        $jsonFile = 'PHP/canciones.json'; 
        $canciones = []; // Inicializa un array vacío para almacenar las canciones

        // Cargar el archivo JSON
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true); // Decodifica el contenido del archivo JSON
            // Verifica que el archivo JSON no esté vacío y que contenga canciones
            if ($data !== null && !empty($data["Canciones"])) {
                $canciones = $data["Canciones"]; // Asigna las canciones al array
            } else {
                // Muestra un mensaje de error si no hay canciones en el archivo
                echo "<h5>Error: No hi ha cançons disponibles per modificar.</h5>";
            }
        } else {
            // Muestra un mensaje de error si no se encuentra el archivo JSON
            echo "<h5>Error: No s'ha trobat el fitxer de cançons.</h5>";
        }

        // Si hay canciones disponibles, muestra la lista para modificar
        if (!empty($canciones)): ?>
            <div class="songs-container">
                <form method="POST">
                    <!-- Recorre cada canción y genera un botón para modificar -->
                    <?php foreach ($canciones as $cancion): ?>
                        <div class="song-item">
                            <!-- Muestra la imagen de portada de la canción -->
                            <img src="<?php echo htmlspecialchars($cancion['portada'] ?? 'ruta/por_defecto.jpg'); ?>" 
                                 alt="Portada de <?php echo htmlspecialchars($cancion['titulo'] ?? ''); ?>">
                            <div class="song-details">
                                <!-- Muestra el título y el autor de la canción -->
                                <h5><?php echo htmlspecialchars($cancion['titulo'] ?? 'Título no disponible'); ?></h5>
                                <p><?php echo htmlspecialchars($cancion['autor'] ?? 'Autor no disponible'); ?></p>
                            </div>
                            <!-- Botón para seleccionar la canción a modificar -->
                            <button type="submit" name="titulo" value="<?php echo htmlspecialchars($cancion['titulo'] ?? ''); ?>" 
                                    class="modify-button">Modificar</button>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        <?php endif; ?>

        <!-- Formulario para modificar los detalles de la canción seleccionada -->
        <div id="modify-form" class="form-container" style="display: none;">
            <form action="./PHP/cancion.php" method="POST" enctype="multipart/form-data">
                <label for="titol">Títol:</label>
                <input type="text" id="titol" name="titol" required>

                <label for="artista">Artista:</label>
                <input type="text" id="artista" name="artista" required>

                <label for="audio">Fitxer de música:</label>
                <input type="file" id="audio" name="audio" accept=".mp3,.json" required>

                <label for="portada">Fitxer de caràtula:</label>
                <input type="file" id="portada" name="portada" accept="image/*" required>

                <label for="arxiu">Fitxer de joc (TXT):</label>
                <input type="file" id="arxiu" name="arxiu" accept=".txt">

                <!-- Botón para enviar el formulario y modificar la canción -->
                <button type="submit">Modificar Cançó</button>
            </form>
            <div id="message"></div> <!-- Espacio para mensajes o notificaciones -->
        </div>

        <!-- PHP para manejar la selección de la canción a modificar -->
        <?php
        // Verifica si se ha enviado el formulario y se ha seleccionado una canción para modificar
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
            $tituloAModificar = htmlspecialchars(trim($_POST['titulo'])); // Sanitiza el título seleccionado

            // Cargar el archivo JSON y buscar la canción correspondiente
            if (file_exists($jsonFile)) {
                $data = json_decode(file_get_contents($jsonFile), true);
                if ($data !== null && !empty($data["Canciones"])) {
                    // Recorre las canciones y busca la que coincide con el título seleccionado
                    foreach ($data["Canciones"] as $cancion) {
                        if (htmlspecialchars($cancion["titulo"]) === $tituloAModificar) {
                            // Usa JavaScript para mostrar el formulario de modificación y rellenar los campos
                            echo "<script>
                                    document.getElementById('titol').value = '".htmlspecialchars($cancion['titulo'])."';
                                    document.getElementById('artista').value = '".htmlspecialchars($cancion['autor'])."';
                                    document.getElementById('modify-form').style.display = 'block';
                                    document.querySelector('.songs-container').style.display = 'none';
                                  </script>";
                            break; // Termina el ciclo una vez encontrada la canción
                        }
                    }
                }
            }
        }
        ?>
    </div>
</body>
</html>
