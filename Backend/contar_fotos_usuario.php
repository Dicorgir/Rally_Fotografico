<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$email = $_GET['email'] ?? '';

if (!$email) {
    echo json_encode(['error' => 'Email no proporcionado']);
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

// Obtener el rally activo
$rally = $mysqli->query("SELECT id_rally, max_fotos_por_participante FROM rallies WHERE CURDATE() BETWEEN fecha_inicio AND fecha_fin LIMIT 1");
if ($rally->num_rows == 0) {
    echo json_encode(['error' => 'No hay rally activo']);
    exit;
}
$rallyData = $rally->fetch_assoc();
$id_rally = $rallyData['id_rally'];
$max_fotos = (int)$rallyData['max_fotos_por_participante'];

// Contar fotos subidas por el usuario en el rally activo
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM fotografias WHERE id_usuario = ? AND id_rally = ?");
$stmt->bind_param("ii", $id_usuario, $id_rally);
$stmt->execute();
$stmt->bind_result($fotos_subidas);
$stmt->fetch();
$stmt->close();

echo json_encode([
    'fotos_subidas' => (int)$fotos_subidas,
    'max_fotos' => $max_fotos
]);
?>