<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar cançó</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .song-item {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        .song-item img {
            max-width: 100px;
            margin-right: 15px;
            border-radius: 10px;
        }

        .song-details {
            flex-grow: 1;
        }

    .delete-button {
        background-color: #ff4d4d;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        margin-left: 10px;
    }

    .delete-button:hover {
        background-color: #ff1a1a;
    }
    .song-details {
    flex-grow: 1;
    min-width: 300px;
}

.song-details h5 {
    font-family: 'Poppins', sans-serif;
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    color: #ffda44;
}

.song-details p {
    font-family: 'Poppins', sans-serif;
    font-size: 1.3rem;
    font-weight: 400;
    margin: 5px 0 0;
    color: #ffffff;
}


.song-item { 
    padding: 15px;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.song-item:hover {
    transform: scale(1.05); 
}

    </style>
</head>
<body>
    <div class="menu-button">
        <a href="inicio.html">TORNAR</a>  
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

        // Si hay canciones, muestra el formulario para eliminarlas
        if (!empty($canciones)): ?>
            <form method="POST" action="">
                <?php foreach ($canciones as $cancion): ?>
                    <div class="song-item">
                        <?php if (isset($cancion['portada'])): ?>
                            <img src="<?php echo htmlspecialchars($cancion['portada']); ?>" alt="Portada de <?php echo htmlspecialchars($cancion['titulo'] ?? ''); ?>">
                        <?php else: ?>
                            <img src="ruta/por_defecto.jpg" alt="Portada no disponible"> <!-- Imagen por defecto si no hay portada -->
                        <?php endif; ?>

                        <div class="song-details">
                            <h5><?php echo htmlspecialchars($cancion['titulo'] ?? 'Título no disponible'); ?></h5>
                            <p><?php echo htmlspecialchars($cancion['autor'] ?? 'Autor no disponible'); ?></p>
                        </div>
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
