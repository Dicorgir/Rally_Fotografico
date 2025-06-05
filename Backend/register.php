<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);

$nombre_completo = $input['nombre_completo'] ?? '';
$email = $input['email'] ?? '';
$telefono = $input['telefono'] ?? null;
$fecha_nacimiento = $input['fecha_nacimiento'] ?? null;
$pais = $input['pais'] ?? null;
$genero = $input['genero'] ?? null;
$foto_perfil = $input['foto_perfil'] ?? null;
$password = $input['password'] ?? '';
$password_confirmation = $input['password_confirmation'] ?? '';

// Validaciones básicas
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

// Verificar si el email ya existe
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

// Insertar usuario
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