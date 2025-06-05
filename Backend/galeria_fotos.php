<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
require_once 'conexion.php'; // Incluye la conexión a la base de datos

$id_rally = $_GET['id_rally'] ?? null; // Obtiene el id del rally desde la URL o null si no existe

// Valida que se haya especificado un rally
if (!$id_rally) {
    echo json_encode(['success' => false, 'message' => 'Rally no especificado', 'fotos' => []]);
    exit;
}

// Prepara la consulta SQL para obtener las fotos admitidas y sus votos del rally seleccionado
$stmt = $mysqli->prepare("
    SELECT f.id_fotografia, f.titulo, f.descripcion, f.imagen_base64, f.total_votos
    FROM fotografias f
    WHERE f.id_rally = ? AND f.estado = 'admitida'
");
$stmt->bind_param("i", $id_rally); // Asocia el id del rally como parámetro a la consulta
$stmt->execute(); // Ejecuta la consulta
$stmt->bind_result($id_fotografia, $titulo, $descripcion, $imagen_base64, $total_votos); // Asocia los resultados a variables

$fotos = []; // Inicializa un array para almacenar las fotos
while ($stmt->fetch()) {
    // Añade cada foto admitida al array de resultados
    $fotos[] = [
        'id_fotografia' => $id_fotografia,
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'imagen_base64' => $imagen_base64,
        'votos' => $total_votos
    ];
}
$stmt->close(); // Cierra la consulta preparada

// Devuelve el array de fotos en formato JSON
echo json_encode(['success' => true, 'fotos' => $fotos]);
?>