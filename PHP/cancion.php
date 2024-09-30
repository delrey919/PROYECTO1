<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Rutas absolutas para almacenar los archivos
    $audioDir = "C:/xampp/htdocs/PROYECTO 1/CANCIONES/";
    $portadaDir = "C:/xampp/htdocs/PROYECTO 1/CANCIONES/";
    $archivoDir = "C:/xampp/htdocs/PROYECTO 1/CANCIONES/";

    // Recogemos los datos del formulario
    $titulo = $_POST['titol'];
    $autor = $_POST['artista'];

    // Guardamos los archivos subidos con verificaciones
    $audioPath = $audioDir . basename($_FILES["audio"]["name"]);
    $portadaPath = $portadaDir . basename($_FILES["portada"]["name"]);
    $archivoPath = $archivoDir . basename($_FILES["arxiu"]["name"]);

    $errores = []; // Array para almacenar errores

    // Subir archivo de audio
    if (is_uploaded_file($_FILES['audio']['tmp_name'])) {
        if (!move_uploaded_file($_FILES["audio"]["tmp_name"], $audioPath)) {
            $errores[] = "Error subiendo el archivo de audio.";
        }
    } else {
        $errores[] = "No se ha subido ningún archivo de audio.";
    }

    // Subir archivo de portada
    if (is_uploaded_file($_FILES['portada']['tmp_name'])) {
        if (!move_uploaded_file($_FILES["portada"]["tmp_name"], $portadaPath)) {
            $errores[] = "Error subiendo el archivo de portada.";
        }
    } else {
        $errores[] = "No se ha subido ningún archivo de portada.";
    }

    // Subir archivo de juego
    if (isset($_FILES['arxiu']) && is_uploaded_file($_FILES['arxiu']['tmp_name'])) {
        if (!move_uploaded_file($_FILES["arxiu"]["tmp_name"], $archivoPath)) {
            $errores[] = "Error subiendo el archivo de juego.";
        }
    }

    // Verificamos el archivo JSON
    $jsonFile = 'canciones.json';
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);
        if ($data === null) {
            $errores[] = "Error al decodificar JSON: " . json_last_error_msg();
        } elseif (!isset($data['Canciones'])) {
            $data['Canciones'] = []; // Asegúrate de que 'Canciones' sea un array
        }
    } else {
        $errores[] = "El archivo JSON no existe.";
        $data = ['Canciones' => []]; // Inicializa 'Canciones' como un array vacío
    }

    // Si no hay errores, añadimos la nueva canción
    if (empty($errores)) {
        $nuevaCancion = array(
            "titulo" => $titulo,
            "autor" => $autor,
            "audio" => $audioPath,
            "portada" => $portadaPath,
            "archivo" => $archivoPath
        );

        // Añadimos la nueva canción al array existente
        array_push($data["Canciones"], $nuevaCancion);

        // Guardamos de nuevo los datos en canciones.json
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if (file_put_contents($jsonFile, $jsonData) === false) {
            $errores[] = "Error guardando el archivo JSON.";
        } else {
            // Redirigimos a "correcto.html" si todo está bien
            header("Location: ../subida.html");
            exit();
        }
    }

    // Si hay errores, los mostramos
    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo $error . "<br>";
        }
    }
}
?>
