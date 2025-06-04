<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once 'conexion.php';
session_start();

// Recibe los datos en JSON
$input = json_decode(file_get_contents('php://input'), true);

$id_fotografia = isset($input['id_fotografia']) ? intval($input['id_fotografia']) : 0;
$comentario = isset($input['comentario']) ? trim($input['comentario']) : '';
$id_usuario = $_SESSION['id_usuario'] ?? null;

if ($id_fotografia <= 0 || $comentario === '') {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$stmt = $mysqli->prepare("INSERT INTO comentarios (id_fotografia, id_usuario, comentario, fecha_comentario) VALUES (?, ?, ?, NOW())");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en prepare: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param("iss", $id_fotografia, $id_usuario, $comentario);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Comentario guardado']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el comentario: ' . $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>