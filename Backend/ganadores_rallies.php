<?php
header('Content-Type: application/json');
include 'conexion.php';

$sql = "SELECT r.nombre AS nombre_rally, f.imagen_base64, u.nombre_completo AS nombre_usuario, f.total_votos
        FROM rallies r
        JOIN fotografias f ON f.id_rally = r.id_rally
        JOIN usuarios u ON f.id_usuario = u.id_usuario
        WHERE f.estado = 'admitida'
        AND f.id_fotografia = (
            SELECT f2.id_fotografia
            FROM fotografias f2
            WHERE f2.id_rally = r.id_rally AND f2.estado = 'admitida'
            ORDER BY f2.total_votos DESC, f2.id_fotografia ASC
            LIMIT 1
        )";

$res = $mysqli->query($sql);
if (!$res) {
    echo json_encode(['error' => $mysqli->error, 'sql' => $sql]);
    $mysqli->close();
    exit;
}
$ganadores = [];
while ($row = $res->fetch_assoc()) {
    $ganadores[] = $row;
}
echo json_encode($ganadores);
$mysqli->close();
?>