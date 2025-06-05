<?php
header('Content-Type: application/json'); // Respuesta en JSON
include 'conexion.php'; // Conexión a la base de datos

$sql = "SELECT id_usuario, nombre_completo, email, telefono, pais, genero, foto_perfil FROM usuarios";
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500); // Error en la consulta
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row; // Añade cada usuario al array
}
echo json_encode($usuarios); // Devuelve el array de usuarios en JSON
$mysqli->close();
?>