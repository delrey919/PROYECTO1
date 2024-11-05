<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar cançó</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

</head>

<body>
    <div class="menu-button">
        <a href="index.html">TORNAR</a>
    </div>

    <div class="container">
        <div class="title">
            <h1>Eliminar cançó</h1>
        </div>

        <?php
        $jsonFile = 'PHP/canciones.json';
        $canciones = [];

        // Cargar el archivo JSON
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true);

            // Verifica si la decodificación fue exitosa
            if ($data === null) {
                echo "<h5>Error: No s'ha pogut llegir el fitxer JSON.</h5>";
                exit;
            }

            // Verifica si hay canciones y las agrega a la lista
            if (isset($data["Canciones"]) && !empty($data["Canciones"])) {
                $canciones = $data["Canciones"];
            } else {
                echo "<h5>Error: No hi ha cançons disponibles per eliminar.</h5>";
            }
        } else {
            echo "<h5>Error: No s'ha trobat el fitxer de cançons.</h5>";
        }

        // Si hay canciones disponibles, muestra el formulario para eliminarlas
        if (!empty($canciones)): ?>
            <!-- Inicia el formulario de eliminación -->
            <form method="POST" action="">
                <!-- Recorre cada canción dentro del array $canciones -->
                <?php foreach ($canciones as $cancion): ?>
                    <div class="song-item">
                        <!-- Verifica si la canción tiene una portada definida -->
                        <?php if (isset($cancion['portada'])): ?>
                            <!-- Si hay portada, muestra la imagen con el atributo "src" -->
                            <img src="<?php echo htmlspecialchars($cancion['portada']); ?>" alt="Portada de <?php echo htmlspecialchars($cancion['titulo'] ?? ''); ?>">
                        <?php else: ?>
                            <!-- Si no hay portada, muestra una imagen por defecto -->
                            <img src="ruta/por_defecto.jpg" alt="Portada no disponible"> <!-- Imagen por defecto si no hay portada -->
                        <?php endif; ?>

                        <div class="song-details">
                            <!-- Muestra el título de la canción o un texto alternativo si no está disponible -->
                            <h5><?php echo htmlspecialchars($cancion['titulo'] ?? 'Título no disponible'); ?></h5>
                            <!-- Muestra el autor de la canción o un texto alternativo si no está disponible -->
                            <p><?php echo htmlspecialchars($cancion['autor'] ?? 'Autor no disponible'); ?></p>
                        </div>
                        <!-- Botón para eliminar la canción, que envía el título de la canción como valor del botón -->
                        <button type="submit" name="titulo" value="<?php echo htmlspecialchars($cancion['titulo'] ?? ''); ?>" class="delete-button">Eliminar</button>
                    </div>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>


        <?php
        // Verifica si se ha enviado el título de la canción a eliminar
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
            $tituloAEliminar = htmlspecialchars(trim($_POST['titulo']));

            // Reemplaza el código anterior de eliminación aquí
            if (file_exists($jsonFile)) {
                $data = json_decode(file_get_contents($jsonFile), true);

                // Verifica si la decodificación fue exitosa
                if ($data === null) {
                    echo "<h5>Error: No s'ha pogut llegir el fitxer JSON.</h5>";
                    exit;
                }

                // Verifica si hay canciones y busca la canción a eliminar
                if (isset($data["Canciones"]) && !empty($data["Canciones"])) {
                    $canciones = $data["Canciones"];
                    $nuevasCanciones = [];

                    foreach ($canciones as $cancion) {
                        if (htmlspecialchars($cancion["titulo"]) !== $tituloAEliminar) {
                            $nuevasCanciones[] = $cancion; // Mantiene la canción si no coincide
                        }
                    }

                    // Actualiza el JSON solo si se encontró la canción
                    if (count($canciones) !== count($nuevasCanciones)) {
                        $data["Canciones"] = $nuevasCanciones; // Reemplaza el array de canciones
                        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT)); // Guarda el archivo
                        echo "<h5>Cançó '$tituloAEliminar' eliminada amb èxit.</h5>";
                    } else {
                        echo "<h5>Error: No s'ha trobat la cançó '$tituloAEliminar'.</h5>";
                    }
                } else {
                    echo "<h5>Error: No hi ha cançons disponibles per eliminar.</h5>";
                }
            } else {
                echo "<h5>Error: No s'ha trobat el fitxer de cançons.</h5>";
            }
        }
        ?>
    </div>
</body>

</html>