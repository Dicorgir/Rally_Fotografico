<?php
header('Content-Type: application/json');
include 'conexion.php';

$sql = "SELECT id_usuario, nombre_completo, email, telefono, pais, genero FROM usuarios";
$result = $mysqli->query($sql);
$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}
echo json_encode($usuarios);
$mysqli->close();
?>