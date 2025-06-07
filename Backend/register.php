<?php
/**
 * register.php
 *
 * Registra un nuevo usuario en el sistema Rally Fotográfico.
 * Recibe los datos del usuario por JSON (POST), valida los campos y guarda el usuario en la base de datos.
 * Devuelve una respuesta JSON indicando el resultado de la operación.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

/**
 * Obtiene los datos del usuario enviados en formato JSON.
 * @var array $input
 */
$input = json_decode(file_get_contents('php://input'), true);

/**
 * Extrae y valida los campos recibidos.
 * @var string $nombre_completo
 * @var string $email
 * @var string|null $telefono
 * @var string|null $fecha_nacimiento
 * @var string|null $pais
 * @var string|null $genero
 * @var string|null $foto_perfil
 * @var string $password
 * @var string $password_confirmation
 */
$nombre_completo = $input['nombre_completo'] ?? '';
$email = $input['email'] ?? '';
$telefono = $input['telefono'] ?? null;
$fecha_nacimiento = $input['fecha_nacimiento'] ?? null;
$pais = $input['pais'] ?? null;
$genero = $input['genero'] ?? null;
$foto_perfil = $input['foto_perfil'] ?? null;
$password = $input['password'] ?? '';
$password_confirmation = $input['password_confirmation'] ?? '';

/**
 * Validaciones básicas de los campos recibidos.
 */
if (!$nombre_completo || !$email || !$password || !$password_confirmation) {
    http_response_code(400);
    echo json_encode(['message' => 'Todos los campos obligatorios deben completarse']);
    exit;
}
if ($password !== $password_confirmation) {
    http_response_code(400);
    echo json_encode(['message' => 'Las contraseñas no coinciden']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'El correo no es válido']);
    exit;
}
if ($pais !== null && strlen($pais) > 50) {
    http_response_code(400);
    echo json_encode(['message' => 'El país no puede tener más de 50 caracteres']);
    exit;
}

// Conexión a la base de datos
include 'conexion.php';

/**
 * Verifica si el email ya existe en la base de datos.
 */
$stmt = $mysqli->prepare('SELECT id_usuario FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['message' => 'El correo ya está registrado']);
    exit;
}
$stmt->close();

/**
 * Inserta el nuevo usuario en la base de datos.
 */
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare('INSERT INTO usuarios (nombre_completo, email, contrasena, telefono, fecha_nacimiento, pais, genero, foto_perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('ssssssss', $nombre_completo, $email, $hash, $telefono, $fecha_nacimiento, $pais, $genero, $foto_perfil);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Registro exitoso']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'Error al registrar usuario']);
}
$stmt->close();
$mysqli->close();
?>