<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_rally = $_GET['id_rally'] ?? null;
if (!$id_rally) {
    echo json_encode(['success' => false, 'message' => 'Rally no especificado', 'fotos' => []]);
    exit;
}

// Obtener fotos admitidas y sus votos del rally seleccionado
$stmt = $mysqli->prepare("
    SELECT f.id_fotografia, f.titulo, f.descripcion, f.imagen_base64, f.total_votos
    FROM fotografias f
    WHERE f.id_rally = ? AND f.estado = 'admitida'
");
$stmt->bind_param("i", $id_rally);
$stmt->execute();
$stmt->bind_result($id_fotografia, $titulo, $descripcion, $imagen_base64, $total_votos);

$fotos = [];
while ($stmt->fetch()) {
    $fotos[] = [
        'id_fotografia' => $id_fotografia,
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'imagen_base64' => $imagen_base64,
        'votos' => $total_votos
    ];
}
$stmt->close();

echo json_encode(['success' => true, 'fotos' => $fotos]);
?>