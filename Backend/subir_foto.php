<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
header('Content-Type: application/json');
require_once 'conexion.php';

// Recoge los datos enviados por POST
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$email = $_POST['email'] ?? '';

// Valida que los datos obligatorios estén presentes
if (!$titulo || !$email || !isset($_FILES['foto'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
    exit;
}

// Busca el id del usuario a partir del email
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}
$stmt->close();

// Valida que se haya seleccionado un rally
$id_rally = $_POST['rally'] ?? null;
if (!$id_rally) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se seleccionó rally']);
    exit;
}

// Valida el formato y tamaño de la imagen
$foto = $_FILES['foto'];
$ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
$permitidos = ['jpg', 'jpeg', 'png'];
if (!in_array($ext, $permitidos)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido']);
    exit;
}
if ($foto['size'] > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La imagen supera el tamaño máximo de 5MB']);
    exit;
}

// Convierte la imagen a base64
$contenido = file_get_contents($foto['tmp_name']);
$base64 = base64_encode($contenido);

// Inserta la foto en la base de datos con estado 'pendiente'
$stmt = $mysqli->prepare("INSERT INTO fotografias (id_usuario, id_rally, titulo, descripcion, imagen_base64, estado, fecha_subida) VALUES (?, ?, ?, ?, ?, 'pendiente', NOW())");
$stmt->bind_param("iisss", $id_usuario, $id_rally, $titulo, $descripcion, $base64);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Foto subida correctamente. Queda pendiente de validación.']);

$stmt->close();
$mysqli->close();
?>