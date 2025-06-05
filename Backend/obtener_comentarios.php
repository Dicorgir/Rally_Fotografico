<?php
header('Content-Type: application/json'); // Respuesta en JSON
require_once 'conexion.php'; // Conexión a la base de datos

$id_fotografia = isset($_GET['id_fotografia']) ? intval($_GET['id_fotografia']) : 0; // Obtiene el id de la foto
if ($id_fotografia <= 0) {
    echo json_encode(['success' => false, 'comentarios' => []]); // Si no es válido, responde vacío
    exit;
}

// Consulta los comentarios de la fotografía
$res = $mysqli->prepare("SELECT comentario, fecha_comentario FROM comentarios WHERE id_fotografia = ? ORDER BY fecha_comentario DESC");
$res->bind_param("i", $id_fotografia);
$res->execute();
$result = $res->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row; // Añade cada comentario al array
}

echo json_encode(['success' => true, 'comentarios' => $comentarios]); // Devuelve los comentarios en JSON
$res->close();
$mysqli->close();
?>