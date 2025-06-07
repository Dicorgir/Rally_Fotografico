<?php
/**
 * fotos_usuario.php
 *
 * Devuelve todas las fotos subidas por un usuario, junto con su información y si pueden ser eliminadas.
 * Recibe el email del usuario por GET.
 * Responde con un array de fotos en formato JSON.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
require_once 'conexion.php'; // Incluye la conexión a la base de datos

/**
 * Obtiene el email del usuario desde la URL o una cadena vacía si no existe.
 * @var string $email
 */
$email = $_GET['email'] ?? '';

/**
 * Valida que el email haya sido proporcionado.
 */
if (!$email) {
    http_response_code(400); // Código HTTP 400: petición incorrecta
    echo json_encode(['success' => false, 'message' => 'Email no proporcionado', 'fotos' => []]);
    exit;
}

/**
 * Obtener id_usuario a partir del email.
 */
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    http_response_code(404); // Código HTTP 404: usuario no encontrado
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado', 'fotos' => []]);
    exit;
}
$stmt->close();

/**
 * Obtener todas las fotos del usuario en todos los rallies.
 */
$stmt = $mysqli->prepare("
    SELECT f.id_fotografia, f.titulo, f.descripcion, f.imagen_base64, f.estado, r.nombre AS nombre_rally, r.fecha_inicio, r.fecha_fin
    FROM fotografias f
    JOIN rallies r ON f.id_rally = r.id_rally
    WHERE f.id_usuario = ?
");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($id_fotografia, $titulo, $descripcion, $imagen_base64, $estado, $nombre_rally, $fecha_inicio, $fecha_fin);

$hoy = date('Y-m-d'); // Obtiene la fecha actual
$fotos = [];
while ($stmt->fetch()) {
    /**
     * Determina si la foto es eliminable según la fecha actual y las fechas del rally.
     * @var bool $eliminable
     */
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

/**
 * Devuelve la lista de fotos en formato JSON.
 */
echo json_encode(['success' => true, 'fotos' => $fotos]);
?>