<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_fotografia = $data['id_fotografia'] ?? null;
$email = $data['email'] ?? '';

if (!$id_fotografia || !$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Obtener id_usuario
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}
$stmt->close();

// Comprobar si la foto pertenece al usuario y obtener la fecha de inicio y fin del rally
$stmt = $mysqli->prepare("
    SELECT f.id_fotografia, r.fecha_inicio, r.fecha_fin
    FROM fotografias f
    JOIN rallies r ON f.id_rally = r.id_rally
    WHERE f.id_fotografia = ? AND f.id_usuario = ?
");
$stmt->bind_param("ii", $id_fotografia, $id_usuario);
$stmt->execute();
$stmt->bind_result($foto_id, $fecha_inicio, $fecha_fin);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Foto no encontrada o no pertenece al usuario']);
    exit;
}
$stmt->close();

// Comprobar si el rally ya ha terminado o no ha empezado
$hoy = date('Y-m-d');
if ($hoy > $fecha_fin) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se puede eliminar la foto porque el rally ya ha finalizado.']);
    exit;
}
if ($hoy < $fecha_inicio) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se puede eliminar la foto porque el rally aún no ha comenzado.']);
    exit;
}

// Eliminar la foto
$stmt = $mysqli->prepare("DELETE FROM fotografias WHERE id_fotografia = ?");
$stmt->bind_param("i", $id_fotografia);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Foto eliminada correctamente.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la foto.']);
}
$stmt->close();
$mysqli->close();
?>