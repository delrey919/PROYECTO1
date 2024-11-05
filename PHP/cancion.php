<?php
// Función para guardar el archivo subido en una ubicación especificada
function saveUploadedFile($file, $directory) {
    // Genera una ruta única para el archivo subido
    $path = $directory . uniqid() . "_" . basename($file["name"]);
    
    // Mueve el archivo subido desde el directorio temporal a la ruta especificada
    if (move_uploaded_file($file["tmp_name"], $path)) {
        return $path; // Si se mueve correctamente, retorna la ruta del archivo
    }
    return false; // Si ocurre un error, retorna false
}

// Verifica si la solicitud es de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define el directorio donde se guardarán los archivos subidos
    $dir = "../CANCIONES/";

    // Recibe los datos enviados desde el formulario (título y autor)
    $titulo = $_POST['titol'];
    $autor = $_POST['artista'];

    // Guardar los archivos subidos (audio, portada y archivo adicional)
    $audioPath = saveUploadedFile($_FILES["audio"], $dir);
    $portadaPath = saveUploadedFile($_FILES["portada"], $dir);
    $archivoPath = saveUploadedFile($_FILES["arxiu"], $dir);

    // Verifica si todos los archivos se han subido correctamente
    if ($audioPath && $portadaPath && $archivoPath) {
        // Especifica la ruta del archivo JSON donde se almacenarán los datos de las canciones
        $jsonFile = 'canciones.json';
        
        // Carga el contenido del archivo JSON si existe, de lo contrario crea un array vacío
        if (file_exists($jsonFile)) {
            $data = json_decode(file_get_contents($jsonFile), true); // Decodifica el archivo JSON en un array asociativo
        } else {
            $data = ["Canciones" => []]; // Si el archivo no existe, inicializa el array con una clave "Canciones"
        }

        // Crea un nuevo array con los datos de la nueva canción
        $nuevaCancion = array(
            "titulo" => $titulo, 
            "autor" => $autor,   
            "audio" => './CANCIONES/' . basename($audioPath),  
            "portada" => './CANCIONES/' . basename($portadaPath), 
            "archivo" => './CANCIONES/' . basename($archivoPath) 
        );

        // Agrega la nueva canción al array de canciones
        array_push($data["Canciones"], $nuevaCancion);

        // Guarda el contenido actualizado en el archivo JSON
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

        // Redirige al usuario a la página de confirmación de subida
        header("Location: ../subida.html");
        exit(); // Termina el script después de redirigir
    } else {
        // Si los archivos no se han subido correctamente, muestra un mensaje de error
        echo "Error al subir los archivos. Verifica los permisos y el tamaño de los archivos.";
    }
}
?>
