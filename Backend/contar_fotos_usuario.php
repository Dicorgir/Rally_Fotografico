<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
require_once 'conexion.php'; // Incluye la conexión a la base de datos

$email = $_GET['email'] ?? ''; // Obtiene el email desde la URL o una cadena vacía si no existe
$id_rally = $_GET['id_rally'] ?? ''; // Obtiene el id del rally desde la URL o una cadena vacía si no existe

// Valida que ambos parámetros estén presentes
if (!$email || !$id_rally) {
    http_response_code(400);
    echo json_encode(['error' => 'Email o ID de rally no proporcionados']);
    exit;
}

// Valida el formato del email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de email no válido']);
    exit;
}

// Valida que el id_rally sea un número entero positivo
if (!ctype_digit($id_rally) || intval($id_rally) <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de rally no válido']);
    exit;
}

// Busca el id_usuario correspondiente al email
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['error' => 'Usuario no encontrado']);
    exit;
}
$stmt->close();

// Cuenta cuántas fotos ha subido el usuario a ese rally
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM fotografias WHERE id_usuario = ? AND id_rally = ?");
$stmt->bind_param("ii", $id_usuario, $id_rally);
$stmt->execute();
$stmt->bind_result($fotos_subidas);
$stmt->fetch();
$stmt->close();

// Obtiene el máximo de fotos permitidas y el nombre del rally
$stmt = $mysqli->prepare("SELECT max_fotos_por_participante, nombre FROM rallies WHERE id_rally = ?");
$stmt->bind_param("i", $id_rally);
$stmt->execute();
$stmt->bind_result($max_fotos, $nombre_rally);
$stmt->fetch();
$stmt->close();

// Devuelve la información en formato JSON
echo json_encode([
    'fotos_subidas' => $fotos_subidas,
    'max_fotos' => $max_fotos,
    'nombre_rally' => $nombre_rally
]);
$mysqli->close(); // Cierra la conexión a la base de datos
?>