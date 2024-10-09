<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUGAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Cambia esto por la ruta de tu CSS principal -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<style>
    .carousel-item {
        display: flex; 
        align-items: center; 
        justify-content: center; 
        flex-wrap: wrap; 
        min-height: 400px; 
        transition: transform 0.5s ease; 
    }

    .carousel-item img {
        max-height: 250px;
        width: auto; 
        margin-right: 20px; 
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .carousel-text {
        display: flex;
        flex-direction: column;
        justify-content: center; 
        max-width: 60%; 
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

    .carousel-item {
        display: none; 
    }

    .carousel-item.active {
        display: flex; 
    }

    .no-songs {
        text-align: center;
        color: #ecf0f1;
        font-size: 2rem;
        margin-top: 20px;
    }
</style>
<body>
    <div class="menu-button">
        <a href="inicio.html">TORNAR</a> 
    </div>   
    <div class="container">
        <div class="title">
            <h1>ESCULL UNA CANÇÓ</h1>
            <div id="carruselCanciones" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $jsonFile = 'PHP/canciones.json';

                    if (!file_exists($jsonFile)) {
                        echo "<div class='no-songs'><h5>Error: No s'ha trobat el fitxer de cançons.</h5></div>";
                    } else {
                        $data = json_decode(file_get_contents($jsonFile), true);

                        // Verifica si la decodificación del JSON fue exitosa
                        if ($data === null) {
                            echo "<div class='no-songs'><h5>Error: No s'ha pogut llegir el fitxer JSON.</h5></div>";
                        } else {
                            // Verifica si hay canciones
                            if (isset($data["Canciones"]) && !empty($data["Canciones"])) {
                                foreach ($data["Canciones"] as $index => $cancion) {
                                    // Verifica si las claves existen antes de acceder a ellas
                                    $titulo = isset($cancion["titulo"]) ? htmlspecialchars($cancion["titulo"]) : '';
                                    $autor = isset($cancion["autor"]) ? htmlspecialchars($cancion["autor"]) : '';
                                    $portada = isset($cancion["portada"]) ? htmlspecialchars($cancion["portada"]) : '';
                                    $audio = isset($cancion["audio"]) ? htmlspecialchars($cancion["audio"]) : ''; 

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
                                    echo '</div>';
                                }
                            } else {
                                // Solo mostrar el mensaje si no hay canciones
                                echo '<div class="carousel-item active">';
                                echo '<div class="carousel-text">';
                                echo '<h5>No hi ha cançons disponibles</h5>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                    }
                    ?>
                </div>

                <!-- Controles del carrusel -->
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
