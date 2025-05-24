<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
header('Content-Type: application/json');
require_once 'conexion.php';

$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$email = $_POST['email'] ?? '';

if (!$titulo || !$email || !isset($_FILES['foto'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
    exit;
}

// Obtener id_usuario
$stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($id_usuario);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    exit;
}
$stmt->close();

$id_rally = $_POST['rally'] ?? null;
if (!$id_rally) {
    echo json_encode(['success' => false, 'message' => 'No se seleccionó rally']);
    exit;
}

// Validar y convertir la imagen a base64
$foto = $_FILES['foto'];
$ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
$permitidos = ['jpg', 'jpeg', 'png'];
if (!in_array($ext, $permitidos)) {
    echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido']);
    exit;
}
if ($foto['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'La imagen supera el tamaño máximo de 5MB']);
    exit;
}
$contenido = file_get_contents($foto['tmp_name']);
$base64 = base64_encode($contenido);

// Insertar en la base de datos (estado pendiente)
$stmt = $mysqli->prepare("INSERT INTO fotografias (id_usuario, id_rally, titulo, descripcion, imagen_base64, estado, fecha_subida) VALUES (?, ?, ?, ?, ?, 'pendiente', NOW())");
$stmt->bind_param("iisss", $id_usuario, $id_rally, $titulo, $descripcion, $base64);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Foto subida correctamente. Queda pendiente de validación.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
}
$stmt->close();
$mysqli->close();
?>