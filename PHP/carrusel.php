<?php

$json = file_get_contents('canciones.json'); 
$canciones = json_decode($json, true);


if ($canciones === null) {
    echo "Error al decodificar el JSON: " . json_last_error_msg();
    $canciones = [];
} else {
    $canciones = $canciones['Canciones'] ?? [];
}
?>

<div id="carruselCanciones" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php if (!empty($canciones)): ?>
            <?php foreach ($canciones as $index => $cancion): ?>
                <div class="carousel-item <?php if ($index == 0) echo 'active'; ?>">
                    <img src="<?php echo $cancion['portada']; ?>" class="d-block w-100" alt="Imagen de <?php echo htmlspecialchars($cancion['titulo']); ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo htmlspecialchars($cancion['titulo']); ?></h5>
                        <p><?php echo htmlspecialchars($cancion['autor']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="carousel-item active">
                <img src="default-image.jpg" class="d-block w-100" alt="Imagen predeterminada">
                <div class="carousel-caption d-none d-md-block">
                    <h5>No hay canciones disponibles</h5>
                </div>
            </div>
        <?php endif; ?>
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
