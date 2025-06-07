<?php
/**
 * login.php
 *
 * Endpoint para autenticar usuarios en el sistema Rally Fotográfico.
 * Recibe email y contraseña por POST (JSON) y devuelve los datos del usuario si las credenciales son correctas.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

// Configuración de cabeceras para la respuesta JSON y CORS
header('Content-Type: application/json'); // Respuesta en JSON
header('Access-Control-Allow-Origin: *'); // Permite peticiones desde cualquier origen

// Solo permite método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}

/**
 * Lee los datos JSON enviados en la petición.
 * @var array $input
 */
$input = json_decode(file_get_contents('php://input'), true);

/**
 * Obtiene el email y la contraseña del usuario.
 * @var string $email
 * @var string $password
 */
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

/**
 * Valida que ambos campos estén presentes.
 */
if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['message' => 'Todos los campos son obligatorios']);
    exit;
}

/**
 * Valida el formato del email.
 */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'El correo no es válido']);
    exit;
}

// Conexión a la base de datos
include 'conexion.php';

/**
 * Busca el usuario por email.
 * @var mysqli_stmt $stmt
 * @var mysqli_result $result
 */
$stmt = $mysqli->prepare('SELECT id_usuario, nombre_completo, contrasena, rol, estado FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

/**
 * Procesa el resultado de la consulta y verifica la contraseña.
 * Devuelve los datos del usuario si el login es exitoso.
 */
if ($row = $result->fetch_assoc()) {
    // Verifica la contraseña
    if (password_verify($password, $row['contrasena'])) {
        // Login exitoso, devuelve datos del usuario
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
        // Contraseña incorrecta
        http_response_code(401);
        echo json_encode(['message' => 'Contraseña incorrecta']);
    }
} else {
    // Usuario no encontrado
    http_response_code(404);
    echo json_encode(['message' => 'Usuario no encontrado']);
}

$stmt->close();
$mysqli->close();
?>