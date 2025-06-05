<?php
header('Content-Type: application/json');
include 'conexion.php';

$sql = "SELECT id_usuario, nombre_completo, email, telefono, pais, genero, foto_perfil FROM usuarios";
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}
echo json_encode($usuarios);
$mysqli->close();
?>