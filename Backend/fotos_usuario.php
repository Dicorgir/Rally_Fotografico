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

// Obtener todas las fotos del usuario en todos los rallies
$stmt = $mysqli->prepare("
    SELECT f.id_fotografia, f.titulo, f.descripcion, f.imagen_base64, f.estado, r.nombre AS nombre_rally, r.fecha_inicio, r.fecha_fin
    FROM fotografias f
    JOIN rallies r ON f.id_rally = r.id_rally
    WHERE f.id_usuario = ?
");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($id_fotografia, $titulo, $descripcion, $imagen_base64, $estado, $nombre_rally, $fecha_inicio, $fecha_fin);

$hoy = date('Y-m-d');
$fotos = [];
while ($stmt->fetch()) {
    $eliminable = ($hoy >= $fecha_inicio && $hoy <= $fecha_fin);
    $fotos[] = [
        'id_fotografia' => $id_fotografia,
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'imagen_base64' => $imagen_base64,
        'estado' => $estado,
        'nombre_rally' => $nombre_rally,
        'eliminable' => $eliminable,
        'fecha_inicio' => $fecha_inicio,
        'fecha_fin' => $fecha_fin
    ];
}
$stmt->close();

echo json_encode(['success' => true, 'fotos' => $fotos]);
?>