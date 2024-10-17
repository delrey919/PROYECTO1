<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTADÍSTIQUES</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #282c34;
            color: #ecf0f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-width: 600px;
        }

        h1 {
            color: #ffcc00;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 1.2rem;
        }

        th {
            background-color: #ffcc00;
            color: #282c34;
            font-weight: bold;
            text-transform: uppercase;
        }

        td {
            background-color: #1c1c1e;
            border-bottom: 1px solid #444;
        }

        tr:hover td {
            background-color: #ffcc00;
            color: black;
            transform: translateY(-3px);
            transition: all 0.3s ease;
        }

        a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: #282c34;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #ffd633;
        }
    </style>
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
        <a href="inicio.html" class="button">Tornar</a>
    </div>
</body>
</html>
