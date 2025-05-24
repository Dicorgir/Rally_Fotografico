<?php
// filepath: c:\xampp\htdocs\Rally_Fotografico\Backend\rallies_todos.php
header('Content-Type: application/json');
require_once 'conexion.php';

$res = $mysqli->query("SELECT id_rally, nombre, fecha_inicio, fecha_fin FROM rallies ORDER BY fecha_inicio ASC");
$rallies = [];
while ($row = $res->fetch_assoc()) {
    $rallies[] = $row;
}
echo json_encode(['success' => true, 'rallies' => $rallies]);
?>