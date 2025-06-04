<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$res = $mysqli->query("SELECT id_rally, nombre, fecha_inicio, fecha_fin, fecha_inicio_votacion, fecha_fin_votacion FROM rallies ORDER BY fecha_inicio DESC");
$rallies = [];
while ($row = $res->fetch_assoc()) {
    $rallies[] = $row;
}
echo json_encode(['success' => true, 'rallies' => $rallies]);
$mysqli->close();
?>