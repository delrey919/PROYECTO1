<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTADÍSTIQUES</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>ESTADÍSTIQUES</h1>
        <table>
            <thead>
                <tr>
                    <th>Jugador</th>
                    <th>Puntuació</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener la cookie 'estadisticas'
                if (isset($_COOKIE['estadisticas'])) {
                    $estadisticas = json_decode($_COOKIE['estadisticas'], true);

                    // Ordenar las estadísticas por puntuación en orden descendente
                    usort($estadisticas, function($a, $b) {
                        return $b['puntuacion'] - $a['puntuacion'];
                    });

                    // Verificar si hay datos
                    if (!empty($estadisticas)) {
                        foreach ($estadisticas as $entry) {
                            $nombre = htmlspecialchars($entry['nombre']);
                            $puntuacion = htmlspecialchars($entry['puntuacion']);
                            echo "<tr><td>$nombre</td><td>$puntuacion</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No hay estadísticas disponibles.</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No se encontraron estadísticas guardadas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.html" class="button">Tornar</a>
    </div>
</body>
</html>
