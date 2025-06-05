<?php
header('Content-Type: application/json'); // Respuesta en JSON
include 'conexion.php'; // Conexión a la base de datos

$sql = "SELECT * FROM rallies"; // Consulta todos los rallies
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500); // Error en la consulta
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

$rallies = [];
while ($row = $result->fetch_assoc()) {
    $rallies[] = $row; // Añade cada rally al array
}
echo json_encode($rallies); // Devuelve el array de rallies en JSON
$mysqli->close();
?>