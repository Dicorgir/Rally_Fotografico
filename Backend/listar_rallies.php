<?php
header('Content-Type: application/json'); // Respuesta en JSON
require_once 'conexion.php'; // Conexión a la base de datos

// Consulta los rallies ordenados por fecha de inicio descendente
$res = $mysqli->query("SELECT id_rally, nombre, fecha_inicio, fecha_fin, fecha_inicio_votacion, fecha_fin_votacion FROM rallies ORDER BY fecha_inicio DESC");

$rallies = [];
while ($row = $res->fetch_assoc()) {
    $rallies[] = $row; // Añade cada rally al array
}

echo json_encode(['success' => true, 'rallies' => $rallies]); // Devuelve los rallies en JSON
$mysqli->close();
?>