<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id_fotografia = isset($_GET['id_fotografia']) ? intval($_GET['id_fotografia']) : 0;
if ($id_fotografia <= 0) {
    echo json_encode(['success' => false, 'comentarios' => []]);
    exit;
}

$res = $mysqli->prepare("SELECT comentario, fecha_comentario FROM comentarios WHERE id_fotografia = ? ORDER BY fecha_comentario DESC");
$res->bind_param("i", $id_fotografia);
$res->execute();
$result = $res->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}

echo json_encode(['success' => true, 'comentarios' => $comentarios]);
$res->close();
$mysqli->close();
?>