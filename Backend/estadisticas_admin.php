<?php
header('Content-Type: application/json');
include 'conexion.php';

$total_usuarios = $mysqli->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];
$total_rallies = $mysqli->query("SELECT COUNT(*) FROM rallies")->fetch_row()[0];
$total_fotos = $mysqli->query("SELECT COUNT(*) FROM fotografias")->fetch_row()[0];
$fotos_admitidas = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='admitida'")->fetch_row()[0];
$fotos_rechazadas = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='rechazada'")->fetch_row()[0];
$fotos_pendientes = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='pendiente'")->fetch_row()[0];

echo json_encode([
    'total_usuarios' => $total_usuarios,
    'total_rallies' => $total_rallies,
    'total_fotos' => $total_fotos,
    'fotos_admitidas' => $fotos_admitidas,
    'fotos_rechazadas' => $fotos_rechazadas,
    'fotos_pendientes' => $fotos_pendientes
]);
$mysqli->close();
?>