<?php
header('Content-Type: application/json');
include 'conexion.php';

$total_usuarios = $mysqli->query("SELECT COUNT(*) FROM usuarios");
$total_rallies = $mysqli->query("SELECT COUNT(*) FROM rallies");
$total_fotos = $mysqli->query("SELECT COUNT(*) FROM fotografias");
$fotos_admitidas = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='admitida'");
$fotos_rechazadas = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='rechazada'");
$fotos_pendientes = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='pendiente'");

if (
    !$total_usuarios || !$total_rallies || !$total_fotos ||
    !$fotos_admitidas || !$fotos_rechazadas || !$fotos_pendientes
) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta de estadísticas']);
    $mysqli->close();
    exit;
}

echo json_encode([
    'total_usuarios' => $total_usuarios->fetch_row()[0],
    'total_rallies' => $total_rallies->fetch_row()[0],
    'total_fotos' => $total_fotos->fetch_row()[0],
    'fotos_admitidas' => $fotos_admitidas->fetch_row()[0],
    'fotos_rechazadas' => $fotos_rechazadas->fetch_row()[0],
    'fotos_pendientes' => $fotos_pendientes->fetch_row()[0]
]);
$mysqli->close();
?>