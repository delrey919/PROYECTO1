<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar cançó</title>
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

        .modify-button {
            background-color: #4d8fff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: auto;
        }

        .modify-button:hover {
            background-color: #007bff;
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

        .form-container {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="menu-button">
        <a href="inicio.html">TORNAR</a>
    </div>

    <div class="container">
        <div class="title">
            <h1>Modificar cançó</h1>
        </div>

        <?php
        $jsonFile = 'PHP/canciones.json';
        $canciones = [];

        // Cargar el archivo JSON
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true);
            if ($data !== null && !empty($data["Canciones"])) {
                $canciones = $data["Canciones"];
            } else {
                echo "<h5>Error: No hi ha cançons disponibles per modificar.</h5>";
            }
        } else {
            echo "<h5>Error: No s'ha trobat el fitxer de cançons.</h5>";
        }

        if (!empty($canciones)): ?>
            <div class="songs-container">
                <form method="POST">
                    <?php foreach ($canciones as $cancion): ?>
                        <div class="song-item">
                            <img src="<?php echo htmlspecialchars($cancion['portada'] ?? 'ruta/por_defecto.jpg'); ?>" 
                                 alt="Portada de <?php echo htmlspecialchars($cancion['titulo'] ?? ''); ?>">
                            <div class="song-details">
                                <h5><?php echo htmlspecialchars($cancion['titulo'] ?? 'Título no disponible'); ?></h5>
                                <p><?php echo htmlspecialchars($cancion['autor'] ?? 'Autor no disponible'); ?></p>
                            </div>
                            <button type="submit" name="titulo" value="<?php echo htmlspecialchars($cancion['titulo'] ?? ''); ?>" 
                                    class="modify-button">Modificar</button>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        <?php endif; ?>

        <div id="modify-form" class="form-container">
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

                <button type="submit">Modificar Cançó</button>
            </form>
            <div id="message"></div>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
            $tituloAModificar = htmlspecialchars(trim($_POST['titulo']));

            if (file_exists($jsonFile)) {
                $data = json_decode(file_get_contents($jsonFile), true);
                if ($data !== null && !empty($data["Canciones"])) {
                    foreach ($data["Canciones"] as $cancion) {
                        if (htmlspecialchars($cancion["titulo"]) === $tituloAModificar) {
                            echo "<script>
                                    document.getElementById('titol').value = '".htmlspecialchars($cancion['titulo'])."';
                                    document.getElementById('artista').value = '".htmlspecialchars($cancion['autor'])."';
                                    document.getElementById('modify-form').style.display = 'block';
                                    document.querySelector('.songs-container').style.display = 'none';
                                  </script>";
                            break;
                        }
                    }
                }
            }
        }
        ?>
    </div>
</body>
</html>
