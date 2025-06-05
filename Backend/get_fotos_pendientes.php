<?php
header('Content-Type: application/json');
include 'conexion.php';

$sql = "SELECT 
            f.id_fotografia, 
            f.imagen_base64, 
            f.estado, 
            u.nombre_completo AS nombre_usuario, 
            r.nombre AS nombre_rally
        FROM fotografias f
        JOIN usuarios u ON f.id_usuario = u.id_usuario
        JOIN rallies r ON f.id_rally = r.id_rally
        WHERE f.estado IN ('pendiente', 'admitida', 'rechazada')";
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

$fotos = [];
while ($row = $result->fetch_assoc()) {
    $fotos[] = $row;
}
echo json_encode($fotos);
$mysqli->close();
?>