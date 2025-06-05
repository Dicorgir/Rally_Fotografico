<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
include 'conexion.php'; // Incluye la conexión a la base de datos

// Obtiene los datos enviados por POST o asigna valores por defecto si no existen
$nombre = $_POST['nombre'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$max_fotos = $_POST['max_fotos_por_participante'] ?? 1;
$fecha_inicio_votacion = $_POST['fecha_inicio_votacion'] ?? '';
$fecha_fin_votacion = $_POST['fecha_fin_votacion'] ?? '';

// Valida que todos los campos obligatorios estén presentes
if (!$nombre || !$fecha_inicio || !$fecha_fin || !$fecha_inicio_votacion || !$fecha_fin_votacion) {
    http_response_code(400); // Código HTTP 400: petición incorrecta
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// Valida que el máximo de fotos sea un número positivo
if (!is_numeric($max_fotos) || intval($max_fotos) < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El máximo de fotos debe ser un número positivo']);
    exit;
}

// Prepara la consulta SQL para insertar el nuevo rally
$stmt = $mysqli->prepare("INSERT INTO rallies (nombre, fecha_inicio, fecha_fin, max_fotos_por_participante, fecha_inicio_votacion, fecha_fin_votacion) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nombre, $fecha_inicio, $fecha_fin, $max_fotos, $fecha_inicio_votacion, $fecha_fin_votacion);

// Ejecuta la consulta y verifica si fue exitosa
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Rally creado correctamente']);
} else {
    http_response_code(500); // Código HTTP 500: error interno del servidor
    echo json_encode(['success' => false, 'message' => 'Error al crear el rally']);
}

// Cierra la consulta preparada y la conexión a la base de datos
$stmt->close();
$mysqli->close();
?>