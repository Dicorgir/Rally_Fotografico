<?php
// filepath: c:\xampp\htdocs\Rally_Fotografico\Backend\rallies_todos.php
header('Content-Type: application/json'); // Respuesta en JSON
require_once 'conexion.php'; // Conexión a la base de datos

// Consulta todos los rallies ordenados por fecha de inicio ascendente
$res = $mysqli->query("SELECT id_rally, nombre, fecha_inicio, fecha_fin FROM rallies ORDER BY fecha_inicio ASC");
if (!$res) {
    http_response_code(500); // Error en la consulta
    echo json_encode(['success' => false, 'error' => 'Error en la consulta: ' . $mysqli->error]);
    exit;
}

$rallies = [];
while ($row = $res->fetch_assoc()) {
    $rallies[] = $row; // Añade cada rally al array
}
echo json_encode(['success' => true, 'rallies' => $rallies]); // Devuelve los rallies en JSON
?>