<?php
header('Content-Type: application/json');
include 'conexion.php';

// Recoge los datos enviados por POST
$id_rally = $_POST['id_rally'] ?? 1;
$nombre = $_POST['nombre'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$max_fotos = $_POST['max_fotos_por_participante'] ?? 1;
$fecha_inicio_votacion = $_POST['fecha_inicio_votacion'] ?? '';
$fecha_fin_votacion = $_POST['fecha_fin_votacion'] ?? '';

// Validar campos obligatorios
if (!$nombre || !$fecha_inicio || !$fecha_fin || !$fecha_inicio_votacion || !$fecha_fin_votacion) {
    http_response_code(400);
    echo json_encode(['message' => 'Faltan campos obligatorios']);
    exit;
}

// Validar formato de fechas
foreach ([$fecha_inicio, $fecha_fin, $fecha_inicio_votacion, $fecha_fin_votacion] as $fecha) {
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$d || $d->format('Y-m-d') !== $fecha) {
        http_response_code(400);
        echo json_encode(['message' => 'Formato de fecha inválido']);
        exit;
    }
}

// Validar max_fotos_por_participante
if (!is_numeric($max_fotos) || intval($max_fotos) < 1) {
    http_response_code(400);
    echo json_encode(['message' => 'El máximo de fotos debe ser un número positivo']);
    exit;
}

// Prepara y ejecuta la consulta para actualizar el rally
$sql = "UPDATE rallies SET nombre=?, fecha_inicio=?, fecha_fin=?, max_fotos_por_participante=?, fecha_inicio_votacion=?, fecha_fin_votacion=? WHERE id_rally=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssissi", $nombre, $fecha_inicio, $fecha_fin, $max_fotos, $fecha_inicio_votacion, $fecha_fin_votacion, $id_rally);

if ($stmt->execute()) {
    echo json_encode(['message' => 'CONFIGURACIÓN ACTUALIZADA CORRECTAMENTE']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'ERROR AL ACTUALIZAR LA CONFIGURACIÓN']);
}
$stmt->close();
$mysqli->close();
?>