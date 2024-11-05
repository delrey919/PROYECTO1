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
                    // Si la cookie 'estadisticas' está definida, decodificar su contenido JSON en un array asociativo
                    $estadisticas = json_decode($_COOKIE['estadisticas'], true);

                    // Ordenar el array $estadisticas por la clave 'puntuacion' en orden descendente
                    usort($estadisticas, function ($a, $b) {
                        return $b['puntuacion'] - $a['puntuacion']; // Compara las puntuaciones para el orden descendente
                    });

                    // Verificar si el array no está vacío
                    if (!empty($estadisticas)) {
                        // Recorre cada entrada en el array $estadisticas
                        foreach ($estadisticas as $entry) {
                            // Valores de 'nombre' y 'puntuacion'
                            $nombre = htmlspecialchars($entry['nombre']);
                            $puntuacion = htmlspecialchars($entry['puntuacion']);

                            // Mostrar una fila de la tabla con los datos del nombre y la puntuación
                            echo "<tr><td>$nombre</td><td>$puntuacion</td></tr>";
                        }
                    } else {
                        // Si no hay estadísticas disponibles, mostrar un mensaje en una fila de la tabla
                        echo "<tr><td colspan='2'>No hay estadísticas disponibles.</td></tr>";
                    }
                } else {
                    // Si la cookie 'estadisticas' no está definida, mostrar un mensaje en una fila de la tabla
                    echo "<tr><td colspan='2'>No se encontraron estadísticas guardadas.</td></tr>";
                }
                ?>
            </tbody>

        </table>
        <a href="index.html" class="button">Tornar</a>
    </div>
</body>

</html>