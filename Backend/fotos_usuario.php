<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$email = $_GET['email'] ?? '';

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email no proporcionado', 'fotos' => []]);
    exit;
}

// Obtener id_usuario
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado', 'fotos' => []]);
    exit;
}
$stmt->close();

// Obtener rally activo
$rally = $mysqli->query("SELECT id_rally FROM rallies WHERE CURDATE() BETWEEN fecha_inicio AND fecha_fin LIMIT 1");
if ($rally->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'No hay rally activo', 'fotos' => []]);
    exit;
}
$id_rally = $rally->fetch_assoc()['id_rally'];

// Obtener fotos del usuario en el rally activo (ahora con nombre del rally)
$stmt = $mysqli->prepare("
    SELECT f.id_fotografia, f.titulo, f.descripcion, f.imagen_base64, f.estado, r.nombre AS nombre_rally
    FROM fotografias f
    JOIN rallies r ON f.id_rally = r.id_rally
    WHERE f.id_usuario = ? AND f.id_rally = ?
");
$stmt->bind_param("ii", $id_usuario, $id_rally);
$stmt->execute();
$stmt->bind_result($id_fotografia, $titulo, $descripcion, $imagen_base64, $estado, $nombre_rally);

$fotos = [];
while ($stmt->fetch()) {
    $fotos[] = [
        'id_fotografia' => $id_fotografia,
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'imagen_base64' => $imagen_base64,
        'estado' => $estado,
        'nombre_rally' => $nombre_rally
    ];
}
$stmt->close();

echo json_encode(['success' => true, 'fotos' => $fotos]);
?>