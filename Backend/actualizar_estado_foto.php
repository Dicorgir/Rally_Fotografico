<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_fotografia = $_POST['id_fotografia'] ?? '';
$estado = $_POST['estado'] ?? '';
if (!in_array($estado, ['pendiente', 'admitida', 'rechazada'])) {
    echo json_encode(['message' => 'Estado no válido']);
    exit;
}
$sql = "UPDATE fotografias SET estado=? WHERE id_fotografia=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $estado, $id_fotografia);
if ($stmt->execute()) {
    echo json_encode(['message' => 'Estado actualizado correctamente']);
} else {
    echo json_encode(['message' => 'Error al actualizar el estado']);
}
$stmt->close();
$mysqli->close();
?>