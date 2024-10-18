<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUGAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body>
    <style>
  h1 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #ffcc00;
        text-align: center;
        margin-top: 20px;
    }

    .carousel-item {
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        min-height: 400px;
        transition: opacity 0.5s ease;
        text-align: center;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }

    .carousel-item.active {
        display: flex;
        position: relative;
        opacity: 1;
    }

    .carousel-item img {
        max-height: 250px;
        width: auto;
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .carousel-text {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        max-width: 80%;
        word-wrap: break-word;
        color: #ffcc00;
        text-align: center;
    }

    .carousel-text h5 {
        font-size: 2rem;
        margin: 0;
        color: #ffcc00;
        font-weight: 700;
    }

    .carousel-text p {
        font-size: 1.5rem;
        color: #ecf0f1;
        margin-top: 5px;
    }

    audio {
        margin-top: 15px;
        max-width: 100%;
        padding: 10px;
    }

    .no-songs {
        text-align: center;
        color: #ecf0f1;
        font-size: 2rem;
        margin-top: 20px;
    }

    .jugar-btn {
        display: block;
        width: 200px;
        margin: 30px auto 0;
        padding: 10px;
        background-color: #ffcc00;
        color: #282c34;
        font-weight: 700;
        font-size: 1.5rem;
        text-align: center;
        text-decoration: none;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease;
    }

    .jugar-btn:hover {
        background-color: #ffd633;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
    }

    .carousel-inner {
        position: relative;
    }
    </style>
    <div class="menu-button">
        <a href="index.html">TORNAR</a>
    </div>
    <div class="container">
        <div class="title">
            <h1>ESCULL UNA CANÇÓ</h1>
            <div id="carruselCanciones" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $jsonFile = 'PHP/canciones.json';

                    if (!file_exists($jsonFile)) {
                        echo "<div class='no-songs'><h5>No s'ha trobat el fitxer de cançons.</h5></div>";
                    } else {
                        $data = json_decode(file_get_contents($jsonFile), true);

                        if ($data === null) {
                            echo "<div class='no-songs'><h5>Error: No s'ha pogut llegir el fitxer JSON.</h5></div>";
                        } else {
                            if (isset($data["Canciones"]) && !empty($data["Canciones"])) {
                                foreach ($data["Canciones"] as $index => $cancion) {
                                    // Verificar que existan las claves antes de usarlas
                                    $titulo = isset($cancion["titulo"]) ? htmlspecialchars($cancion["titulo"]) : 'Sin título';
                                    $autor = isset($cancion["autor"]) ? htmlspecialchars($cancion["autor"]) : 'Autor desconocido';
                                    $portada = isset($cancion["portada"]) ? htmlspecialchars($cancion["portada"]) : '';
                                    $audio = isset($cancion["audio"]) ? htmlspecialchars($cancion["audio"]) : '';
                                    $archivo = isset($cancion["archivo"]) ? htmlspecialchars($cancion["archivo"]) : '';

                                    echo '<div class="carousel-item' . ($index === 0 ? ' active' : '') . '">';
                                    echo '<img src="' . $portada . '" alt="Portada de ' . $titulo . '">';
                                    echo '<div class="carousel-text">';
                                    echo '<h5>' . $titulo . '</h5>';
                                    echo '<p>' . $autor . '</p>';
                                    echo '<audio controls>';
                                    echo '<source src="' . $audio . '" type="audio/mpeg">';
                                    echo 'Tu navegador no soporta el elemento de audio.';
                                    echo '</audio>';
                                    echo '</div>';
                                    echo '<a href="joc.html?titulo=' . urlencode($titulo) . '&autor=' . urlencode($autor) . '&audio=' . urlencode($audio) . '&archivo=' . urlencode($archivo) . '&portada=' . urlencode($portada) . '" class="jugar-btn">JUGAR</a>';
                                    echo '</div>';
                                }
                            }
                        }
                    }
                    ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carruselCanciones" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carruselCanciones" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>