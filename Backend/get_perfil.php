<?php
header('Content-Type: application/json'); // Respuesta en JSON
include 'conexion.php'; // Conexión a la base de datos

// Comprueba que se haya enviado el parámetro email
if (!isset($_GET['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta el parámetro email']);
    exit;
}

$email = $_GET['email'];
// Valida el formato del email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de email no válido']);
    exit;
}

// Prepara la consulta para buscar el perfil del usuario
$sql = "SELECT nombre_completo, email, telefono, fecha_nacimiento, pais, genero, foto_perfil FROM usuarios WHERE email = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en prepare: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Si encuentra el usuario, devuelve sus datos
if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    // Si no existe, responde con 404
    http_response_code(404);
    echo json_encode(['error' => 'Usuario no encontrado']);
}
$stmt->close();
$mysqli->close();
?>