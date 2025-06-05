<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_fotografia = $data['id_fotografia'] ?? null;
$ip = $_SERVER['REMOTE_ADDR']; // IP del usuario

if (!$id_fotografia) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Verifica si existe el voto de esta IP para la foto
$stmt = $mysqli->prepare("SELECT 1 FROM votaciones WHERE id_fotografia = ? AND ip = ?");
$stmt->bind_param("is", $id_fotografia, $ip);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'No has votado por esta foto.']);
    $stmt->close();
    exit;
}
$stmt->close();

// Elimina el voto
$stmt = $mysqli->prepare("DELETE FROM votaciones WHERE id_fotografia = ? AND ip = ?");
$stmt->bind_param("is", $id_fotografia, $ip);
if ($stmt->execute()) {
    // Resta el voto a la foto (si tiene votos)
    $mysqli->query("UPDATE fotografias SET total_votos = total_votos - 1 WHERE id_fotografia = $id_fotografia AND total_votos > 0");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al quitar el voto']);
}
$stmt->close();
$mysqli->close();
?>