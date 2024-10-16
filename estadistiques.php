<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
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

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            background-color: #1c1c1e;
            margin-bottom: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease;
        }

        li:hover {
            transform: translateY(-5px);
            background-color: #ffcc00;
            color: black;
        }

        .username {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .score {
            font-size: 1.2rem;
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
        <h1>Estadísticas</h1>
        <ul>
            <?php
            // Obtener la cookie 'estadisticas'
            if (isset($_COOKIE['estadisticas'])) {
                $estadisticas = json_decode($_COOKIE['estadisticas'], true);
                if (!empty($estadisticas)) {
                    foreach ($estadisticas as $entry) {
                        $nombre = htmlspecialchars($entry['nombre']);
                        $puntuacion = htmlspecialchars($entry['puntuacion']);
                        echo "<li><span class='username'>$nombre</span> <span class='score'>--> $puntuacion</span></li>";
                    }
                } else {
                    echo "<li>No hay estadísticas disponibles.</li>";
                }
            } else {
                echo "<li>No se encontraron estadísticas guardadas.</li>";
            }
            ?>
        </ul>
        <a href="inicio.html" class="button">Tornar</a>
    </div>
</body>
</html>
