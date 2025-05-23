<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);
$id_fotografia = $data['id_fotografia'] ?? null;
$ip = $_SERVER['REMOTE_ADDR'];

if (!$id_fotografia) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Comprobar si la votación está activa
$rally = $mysqli->query("
    SELECT r.fecha_inicio_votacion, r.fecha_fin_votacion
    FROM rallies r
    JOIN fotografias f ON f.id_rally = r.id_rally
    WHERE f.id_fotografia = $id_fotografia
    LIMIT 1
");
if ($rally->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'No se encontró el rally']);
    exit;
}
$fechas = $rally->fetch_assoc();
$hoy = date('Y-m-d');
if ($hoy < $fechas['fecha_inicio_votacion'] || $hoy > $fechas['fecha_fin_votacion']) {
    echo json_encode(['success' => false, 'message' => 'La votación no está activa']);
    exit;
}

// Intentar insertar el voto (un voto por IP por foto)
$stmt = $mysqli->prepare("INSERT INTO votaciones (id_fotografia, ip) VALUES (?, ?)");
$stmt->bind_param("is", $id_fotografia, $ip);

if ($stmt->execute()) {
    // Sumar el voto a la foto
    $mysqli->query("UPDATE fotografias SET total_votos = total_votos + 1 WHERE id_fotografia = $id_fotografia");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ya has votado por esta foto desde esta IP']);
}
$stmt->close();
$mysqli->close();
?>