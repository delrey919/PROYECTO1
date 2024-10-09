<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cambiar la ruta a la carpeta CANCIONES desde la carpeta PHP
    $dir = "../CANCIONES/";

    // Usamos 'titol' y 'artista' de acuerdo a los nombres en el HTML
    $titulo = $_POST['titol'];
    $autor = $_POST['artista'];

    // Generamos rutas únicas para los archivos
    $audioPath = $dir . uniqid() . "_" . basename($_FILES["audio"]["name"]);
    $portadaPath = $dir . uniqid() . "_" . basename($_FILES["portada"]["name"]);
    $archivoPath = $dir . uniqid() . "_" . basename($_FILES["arxiu"]["name"]);

    // Intentamos mover los archivos subidos
    if (
        move_uploaded_file($_FILES["audio"]["tmp_name"], $audioPath) &&
        move_uploaded_file($_FILES["portada"]["tmp_name"], $portadaPath) &&
        move_uploaded_file($_FILES["arxiu"]["tmp_name"], $archivoPath)
    ) {
        // Agrega depuración aquí para verificar las rutas
        echo "Audio: $audioPath<br>";
        echo "Portada: $portadaPath<br>";
        echo "Archivo: $archivoPath<br>";

        // Cargar el archivo JSON
        $jsonFile = 'canciones.json'; // Ruta para cargar el archivo JSON
        // Verifica si el archivo existe y está en el formato correcto
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true);
        } else {
            // Si no existe, inicializa la estructura del JSON
            $data = ["Canciones" => []];
        }

        // Creamos una nueva canción
        $nuevaCancion = array(
            "titulo" => $titulo,
            "autor" => $autor,
            "audio" => './CANCIONES/' . basename($audioPath), // Ruta relativa al archivo de audio
            "portada" => './CANCIONES/' . basename($portadaPath), // Ruta relativa a la portada
            "archivo" => './CANCIONES/' . basename($archivoPath)  // Ruta relativa al archivo
        );

        // Añadimos la nueva canción al array
        array_push($data["Canciones"], $nuevaCancion);

        // Guardamos los cambios en el archivo JSON
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

        // Redirigimos a la página de confirmación
        header("Location: ../subida.html");
        exit();
    } else {
        // Manejo de error en caso de que no se puedan mover los archivos
        echo "Error al subir los archivos. Verifica los permisos y el tamaño de los archivos.";
    }
}
?>
