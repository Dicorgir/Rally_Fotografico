<?php
header('Content-Type: application/json');
require_once 'conexion.php';

// Obtener rally activo (puedes ajustar la lógica según tus reglas)
$rally = $mysqli->query("SELECT id_rally FROM rallies WHERE CURDATE() BETWEEN fecha_inicio AND fecha_fin LIMIT 1");
if ($rally->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'No hay rally activo', 'fotos' => []]);
    exit;
}
$id_rally = $rally->fetch_assoc()['id_rally'];

// Obtener fotos admitidas y sus votos
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