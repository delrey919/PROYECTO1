<?php
// Leer el archivo JSON
$json = file_get_contents('PHP\cancion.php');
$canciones = json_decode($json, true);
?>

<div id="carruselCanciones" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($canciones as $index => $cancion[]): ?>
            <div class="carousel-item <?php if ($index == 0) echo 'active'; ?>">
                <img src="<?php echo $cancion['portada']; ?>" class="d-block w-100" alt="Imagen de <?php echo $cancion['titol']; ?>">
                <div class="carousel-caption d-none d-md-block">
                    <h5><?php echo $cancion['titol']; ?></h5>
                    <p><?php echo $cancion['artista']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carruselCanciones" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carruselCanciones" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</div>
