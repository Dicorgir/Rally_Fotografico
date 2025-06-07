<?php
/**
 * guardar_comentario.php
 *
 * Guarda un comentario realizado por un usuario sobre una fotografía.
 * Recibe el id de la fotografía y el comentario por JSON (POST).
 * Utiliza la sesión para identificar al usuario.
 * Devuelve una respuesta JSON indicando el resultado de la operación.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Respuesta en JSON
require_once 'conexion.php'; // Conexión a la base de datos
session_start(); // Inicia la sesión para obtener el usuario

date_default_timezone_set('Europe/Madrid');

/**
 * Recibe los datos en JSON desde el cuerpo de la petición.
 * @var array $input
 */
$input = json_decode(file_get_contents('php://input'), true);

/**
 * Obtiene el id de la fotografía, el comentario y el id del usuario.
 * @var int $id_fotografia
 * @var string $comentario
 * @var int|null $id_usuario
 */
$id_fotografia = isset($input['id_fotografia']) ? intval($input['id_fotografia']) : 0;
$comentario = isset($input['comentario']) ? trim($input['comentario']) : '';
$id_usuario = $_SESSION['id_usuario'] ?? null;

/**
 * Valida que los datos sean correctos.
 */
if ($id_fotografia <= 0 || $comentario === '') {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

/**
 * Fecha y hora actual en España.
 * @var string $fecha_comentario
 */
$fecha_comentario = date('Y-m-d H:i:s');

/**
 * Prepara la consulta para guardar el comentario.
 * @var mysqli_stmt|false $stmt
 */
$stmt = $mysqli->prepare("INSERT INTO comentarios (id_fotografia, id_usuario, comentario, fecha_comentario) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en prepare: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param("isss", $id_fotografia, $id_usuario, $comentario, $fecha_comentario);

/**
 * Ejecuta la consulta y responde según el resultado.
 */
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Comentario guardado']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el comentario: ' . $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>