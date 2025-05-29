<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_usuario = $_POST['id_usuario'] ?? '';
$nombre = $_POST['nombre_completo'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$pais = $_POST['pais'] ?? '';
$genero = $_POST['genero'] ?? '';

$sql = "UPDATE usuarios SET nombre_completo=?, email=?, telefono=?, pais=?, genero=? WHERE id_usuario=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssssi", $nombre, $email, $telefono, $pais, $genero, $id_usuario);

if ($stmt->execute()) {
    echo json_encode(['message' => 'USUARIO ACTUALIZADO CORRECTAMENTE']);
} else {
    echo json_encode(['message' => 'ERROR AL ACTUALIZAR EL USUARIO']);
}
$stmt->close();
$mysqli->close();
?>