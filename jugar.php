<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JUGAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .carousel-item {
    display: flex; 
    align-items: center; 
    justify-content: center; 
    flex-wrap: wrap; 
    min-height: 300px; 
}

.carousel-item img {
    max-height: 150px;
    width: auto; 
    margin-right: 20px; 
    object-fit: cover; 
}

.carousel-text {
    display: flex;
    flex-direction: column;
    justify-content: center; 
    max-width: 60%; 
    word-wrap: break-word; 
    color: yellow;
    text-align: center; 
}

.carousel-text h5 {
    font-size: 1.5rem;
    margin: 0; 
    color: #ffcc00;
}

.carousel-text p {
    font-size: 1rem; 
    color: white; 
    margin-top: 5px; 
}

audio {
    margin-top: 15px;
    width: 100%;
    max-width: 100%;
    border: 1px solid #ffcc00; /
    border-radius: 5px; /
    background-color: rgba(255, 255, 255, 0.2); 
    padding: 10px;
}

.carousel-control-prev-icon, .carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5); 
    border-radius: 50%;
}

.carousel-item {
    display: none; 
}

.carousel-item.active {
    display: flex; 
}


    </style>
</head>
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
                    $jsonFile = './PHP/canciones.json';
                    $data = json_decode(file_get_contents($jsonFile), true);
                    
                    // Verifica si hay canciones
                    if (isset($data["Canciones"]) && !empty($data["Canciones"])) {
                        foreach ($data["Canciones"] as $index => $cancion) {
                            // Verifica si las claves existen antes de acceder a ellas
                            $titulo = isset($cancion["titulo"]) ? htmlspecialchars($cancion["titulo"]) : 'Título desconocido';
                            $autor = isset($cancion["autor"]) ? htmlspecialchars($cancion["autor"]) : 'Autor desconocido';
                            $portada = isset($cancion["portada"]) ? htmlspecialchars($cancion["portada"]) : 'default-image.jpg';
                            $audio = isset($cancion["audio"]) ? htmlspecialchars($cancion["audio"]) : '#'; // Enlace a un audio por defecto
                    
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
                    }
                    else {
                        echo '<div class="carousel-item active">';
                        echo '<img src="default-image.jpg" alt="Imagen predeterminada">';
                        echo '<div class="carousel-text">';
                        echo '<h5>No hay canciones disponibles</h5>';
                        echo '</div>';
                        echo '</div>';
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
