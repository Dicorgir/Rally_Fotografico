<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_usuario = $_POST['id_usuario'] ?? '';

if (!$id_usuario || !is_numeric($id_usuario)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido']);
    exit;
}

$sql = "DELETE FROM usuarios WHERE id_usuario=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
}
$stmt->close();
$mysqli->close();
?>