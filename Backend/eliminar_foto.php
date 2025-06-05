<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
require_once 'conexion.php'; // Incluye la conexión a la base de datos

// Obtiene los datos enviados en formato JSON desde el cuerpo de la petición
$data = json_decode(file_get_contents('php://input'), true);
// Extrae el id de la fotografía y el email del usuario del array recibido
$id_fotografia = $data['id_fotografia'] ?? null;
$email = $data['email'] ?? '';

// Valida que ambos datos sean proporcionados
if (!$id_fotografia || !$email) {
    http_response_code(400); // Código HTTP 400: petición incorrecta
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Busca el id_usuario correspondiente al email proporcionado
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    http_response_code(404); // Código HTTP 404: usuario no encontrado
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}
$stmt->close();

// Comprueba si la foto pertenece al usuario y obtiene las fechas del rally asociado
$stmt = $mysqli->prepare("
    SELECT f.id_fotografia, r.fecha_inicio, r.fecha_fin
    FROM fotografias f
    JOIN rallies r ON f.id_rally = r.id_rally
    WHERE f.id_fotografia = ? AND f.id_usuario = ?
");
$stmt->bind_param("ii", $id_fotografia, $id_usuario);
$stmt->execute();
$stmt->bind_result($foto_id, $fecha_inicio, $fecha_fin);
if (!$stmt->fetch()) {
    http_response_code(404); // Código HTTP 404: foto no encontrada o no pertenece al usuario
    echo json_encode(['success' => false, 'message' => 'Foto no encontrada o no pertenece al usuario']);
    exit;
}
$stmt->close();

// Comprueba si el rally ya ha terminado o aún no ha comenzado
$hoy = date('Y-m-d');
if ($hoy > $fecha_fin) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se puede eliminar la foto porque el rally ya ha finalizado.']);
    exit;
}
if ($hoy < $fecha_inicio) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se puede eliminar la foto porque el rally aún no ha comenzado.']);
    exit;
}

// Elimina la foto de la base de datos
$stmt = $mysqli->prepare("DELETE FROM fotografias WHERE id_fotografia = ?");
$stmt->bind_param("i", $id_fotografia);
if ($stmt->execute()) {
    // Si la eliminación fue exitosa, devuelve un mensaje de éxito
    echo json_encode(['success' => true, 'message' => 'Foto eliminada correctamente.']);
} else {
    // Si hubo un error al eliminar, responde con código 500 y mensaje de error
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la foto.']);
}
$stmt->close();
$mysqli->close(); // Cierra la conexión a la base de datos
?>