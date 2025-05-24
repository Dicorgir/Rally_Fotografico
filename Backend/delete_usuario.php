<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_usuario = $_POST['id_usuario'] ?? '';
$sql = "DELETE FROM usuarios WHERE id_usuario=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
}
$stmt->close();
$mysqli->close();
?>