<?php
// Función para guardar los archivos subidos
/**
 * Sube un archivo al servidor y retorna la ruta del archivo.
 * @param array $file El archivo a subir.
 * @param string $directory La carpeta donde se guardará el archivo.
 * @return string|false La ruta del archivo si se sube correctamente, false en caso de error.
 */
function saveUploadedFile($file, $directory) {
    $path = $directory . uniqid() . "_" . basename($file["name"]);
    if (move_uploaded_file($file["tmp_name"], $path)) {
        return $path;
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dir = "../CANCIONES/";

    $titulo = $_POST['titol'];
    $autor = $_POST['artista'];

    // Guardar los archivos subidos
    $audioPath = saveUploadedFile($_FILES["audio"], $dir);
    $portadaPath = saveUploadedFile($_FILES["portada"], $dir);
    $archivoPath = saveUploadedFile($_FILES["arxiu"], $dir);

    if ($audioPath && $portadaPath && $archivoPath) {
        // Cargar el archivo JSON
        $jsonFile = 'canciones.json';
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true);
        } else {
            $data = ["Canciones" => []];
        }

        // Nueva canción
        $nuevaCancion = array(
            "titulo" => $titulo,
            "autor" => $autor,
            "audio" => './CANCIONES/' . basename($audioPath),
            "portada" => './CANCIONES/' . basename($portadaPath),
            "archivo" => './CANCIONES/' . basename($archivoPath)
        );

        array_push($data["Canciones"], $nuevaCancion);

        // Guardar cambios
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

        // Redirigir a la página de confirmación
        header("Location: ../subida.html");
        exit();
    } else {
        echo "Error al subir los archivos. Verifica los permisos y el tamaño de los archivos.";
    }
}
?>
