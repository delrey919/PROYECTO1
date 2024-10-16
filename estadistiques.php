<?php
// Ruta del archivo donde se guardarán las estadísticas
$estadisticasFile = 'estadisticas.json';

// Obtener los datos enviados
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convertir JSON en array asociativo

if ($input !== null) {
    // Leer el archivo existente
    if (file_exists($estadisticasFile)) {
        $data = json_decode(file_get_contents($estadisticasFile), true);
    } else {
        $data = [];
    }

    // Agregar la nueva estadística
    $data[] = $input;

    // Guardar el archivo actualizado
    file_put_contents($estadisticasFile, json_encode($data, JSON_PRETTY_PRINT));

    // Responder al cliente
    echo json_encode(['status' => 'success']);
} else {
    // Si no se puede decodificar el JSON, devolver un error
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
}
