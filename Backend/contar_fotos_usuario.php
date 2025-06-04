<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$email = $_GET['email'] ?? '';
$id_rally = $_GET['id_rally'] ?? '';

if (!$email || !$id_rally) {
    echo json_encode(['error' => 'Email o ID de rally no proporcionados']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Formato de email no válido']);
    exit;
}
if (!ctype_digit($id_rally) || intval($id_rally) <= 0) {
    echo json_encode(['error' => 'ID de rally no válido']);
    exit;
}

// Obtener el id_usuario
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    echo json_encode(['error' => 'Usuario no encontrado']);
    exit;
}
$stmt->close();

// Contar fotos subidas por usuario a ese rally
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM fotografias WHERE id_usuario = ? AND id_rally = ?");
$stmt->bind_param("ii", $id_usuario, $id_rally);
$stmt->execute();
$stmt->bind_result($fotos_subidas);
$stmt->fetch();
$stmt->close();

// Obtener el máximo de fotos permitidas para ese rally
$stmt = $mysqli->prepare("SELECT max_fotos_por_participante, nombre FROM rallies WHERE id_rally = ?");
$stmt->bind_param("i", $id_rally);
$stmt->execute();
$stmt->bind_result($max_fotos, $nombre_rally);
$stmt->fetch();
$stmt->close();

echo json_encode([
    'fotos_subidas' => $fotos_subidas,
    'max_fotos' => $max_fotos,
    'nombre_rally' => $nombre_rally
]);
$mysqli->close();
?>