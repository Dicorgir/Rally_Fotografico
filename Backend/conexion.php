<?php
$mysqli = new mysqli('localhost', 'root', '', 'rally_fotografico', 3307);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(['message' => 'Error de conexión a la base de datos']);
    exit;
}
?>