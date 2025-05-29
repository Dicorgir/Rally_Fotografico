<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_rally = $_POST['id_rally'] ?? 1;
$nombre = $_POST['nombre'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$max_fotos = $_POST['max_fotos_por_participante'] ?? 1;
$fecha_inicio_votacion = $_POST['fecha_inicio_votacion'] ?? '';
$fecha_fin_votacion = $_POST['fecha_fin_votacion'] ?? '';

$sql = "UPDATE rallies SET nombre=?, fecha_inicio=?, fecha_fin=?, max_fotos_por_participante=?, fecha_inicio_votacion=?, fecha_fin_votacion=? WHERE id_rally=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssissi", $nombre, $fecha_inicio, $fecha_fin, $max_fotos, $fecha_inicio_votacion, $fecha_fin_votacion, $id_rally);

if ($stmt->execute()) {
    echo json_encode(['message' => 'CONFIGURACIÓN ACTUALIZADA CORRECTAMENTE']);
} else {
    echo json_encode(['message' => 'ERROR AL ACTUALIZAR LA CONFIGURACIÓN']);
}
$stmt->close();
$mysqli->close();
?>