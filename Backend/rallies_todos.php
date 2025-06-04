<?php
// filepath: c:\xampp\htdocs\Rally_Fotografico\Backend\rallies_todos.php
header('Content-Type: application/json');
require_once 'conexion.php';

$res = $mysqli->query("SELECT id_rally, nombre, fecha_inicio, fecha_fin FROM rallies ORDER BY fecha_inicio ASC");
if (!$res) {
    echo json_encode(['success' => false, 'error' => 'Error en la consulta: ' . $mysqli->error]);
    exit;
}

$rallies = [];
while ($row = $res->fetch_assoc()) {
    $rallies[] = $row;
}
echo json_encode(['success' => true, 'rallies' => $rallies]);
?>