<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_fotografia = $data['id_fotografia'] ?? null;
$email = $data['email'] ?? '';

if (!$id_fotografia || !$email) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Obtener id_usuario
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}
$stmt->close();

// Eliminar solo si la foto pertenece al usuario
$stmt = $mysqli->prepare("DELETE FROM fotografias WHERE id_fotografia = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_fotografia, $id_usuario);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la foto']);
}
$stmt->close();
$mysqli->close();
?>