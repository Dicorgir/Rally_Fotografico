<?php
header('Content-Type: application/json');
include 'conexion.php';

$sql = "SELECT * FROM rallies";
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

$rallies = [];
while ($row = $result->fetch_assoc()) {
    $rallies[] = $row;
}
echo json_encode($rallies);
$mysqli->close();
?>