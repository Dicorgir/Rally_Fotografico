<?php
/**
 * votar_foto.php
 *
 * Permite votar por una fotografía de un rally, controlando que solo se permita un voto por IP en cada rally.
 * Recibe el id de la fotografía por JSON (POST).
 * Devuelve una respuesta JSON indicando el resultado de la operación.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json');
require_once 'conexion.php';

/**
 * Obtiene los datos enviados en formato JSON desde el cuerpo de la petición.
 * @var array $data
 * @var int|null $id_fotografia
 * @var string $ip
 */
$data = json_decode(file_get_contents('php://input'), true);
$id_fotografia = $data['id_fotografia'] ?? null;
$ip = $_SERVER['REMOTE_ADDR']; // IP del usuario

if (!$id_fotografia) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

/**
 * Obtener el id_rally de la foto seleccionada.
 * @var int $id_rally
 */
$stmt = $mysqli->prepare("SELECT id_rally FROM fotografias WHERE id_fotografia = ?");
$stmt->bind_param("i", $id_fotografia);
$stmt->execute();
$stmt->bind_result($id_rally);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Fotografía no encontrada']);
    $stmt->close();
    exit;
}
$stmt->close();

/**
 * Comprobar si la votación está activa para el rally.
 */
$rally = $mysqli->query("
    SELECT fecha_inicio_votacion, fecha_fin_votacion
    FROM rallies
    WHERE id_rally = $id_rally
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

/**
 * Verificar si la IP ya votó por alguna foto de este rally.
 */
$stmt = $mysqli->prepare("
    SELECT v.id_fotografia
    FROM votaciones v
    JOIN fotografias f ON v.id_fotografia = f.id_fotografia
    WHERE f.id_rally = ? AND v.ip = ?
    LIMIT 1
");
$stmt->bind_param("is", $id_rally, $ip);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Solo se permite un voto por IP en este rally. Si quieres cambiar tu voto, primero debes quitar el anterior.']);
    $stmt->close();
    exit;
}
$stmt->close();

/**
 * Insertar el voto en la base de datos.
 */
$stmt = $mysqli->prepare("INSERT INTO votaciones (id_fotografia, ip) VALUES (?, ?)");
$stmt->bind_param("is", $id_fotografia, $ip);

if ($stmt->execute()) {
    // Sumar el voto a la foto
    $mysqli->query("UPDATE fotografias SET total_votos = total_votos + 1 WHERE id_fotografia = $id_fotografia");
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el voto']);
}
$stmt->close();
$mysqli->close();
?>