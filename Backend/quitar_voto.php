<?php
/**
 * quitar_voto.php
 *
 * Elimina el voto de una fotografía realizado por la IP actual.
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
 */
$data = json_decode(file_get_contents('php://input'), true);

/**
 * Obtiene el id de la fotografía y la IP del usuario.
 * @var int|null $id_fotografia
 * @var string $ip
 */
$id_fotografia = $data['id_fotografia'] ?? null;
$ip = $_SERVER['REMOTE_ADDR']; // IP del usuario

/**
 * Valida que se haya recibido el id de la fotografía.
 */
if (!$id_fotografia) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

/**
 * Verifica si existe el voto de esta IP para la foto.
 * @var mysqli_stmt $stmt
 */
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

/**
 * Elimina el voto de la base de datos.
 */
$stmt = $mysqli->prepare("DELETE FROM votaciones WHERE id_fotografia = ? AND ip = ?");
$stmt->bind_param("is", $id_fotografia, $ip);

/**
 * Ejecuta la consulta y responde según el resultado.
 */
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