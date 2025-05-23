<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['message' => 'Todos los campos son obligatorios']);
    exit;
}

// Conexión a la base de datos centralizada
include 'conexion.php';

$stmt = $mysqli->prepare('SELECT id_usuario, nombre_completo, contrasena, rol, estado FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['contrasena'])) {
        echo json_encode([
            'message' => 'Login exitoso',
            'usuario' => [
                'id' => $row['id_usuario'],
                'nombre_completo' => $row['nombre_completo'],
                'rol' => $row['rol'],
                'estado' => $row['estado']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Contraseña incorrecta']);
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Usuario no encontrado']);
}

$stmt->close();
$mysqli->close();