<?php
header('Content-Type: application/json');
include 'conexion.php';

$nombre = $_POST['nombre'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$max_fotos = $_POST['max_fotos_por_participante'] ?? 1;
$fecha_inicio_votacion = $_POST['fecha_inicio_votacion'] ?? '';
$fecha_fin_votacion = $_POST['fecha_fin_votacion'] ?? '';

if (!$nombre || !$fecha_inicio || !$fecha_fin || !$fecha_inicio_votacion || !$fecha_fin_votacion) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

if (!is_numeric($max_fotos) || intval($max_fotos) < 1) {
    echo json_encode(['success' => false, 'message' => 'El máximo de fotos debe ser un número positivo']);
    exit;
}

$stmt = $mysqli->prepare("INSERT INTO rallies (nombre, fecha_inicio, fecha_fin, max_fotos_por_participante, fecha_inicio_votacion, fecha_fin_votacion) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nombre, $fecha_inicio, $fecha_fin, $max_fotos, $fecha_inicio_votacion, $fecha_fin_votacion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Rally creado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear el rally']);
}
$stmt->close();
$mysqli->close();
?>