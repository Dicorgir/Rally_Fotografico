<?php
/**
 * ganadores_rallies.php
 *
 * Devuelve el ganador de cada rally (foto admitida con más votos) junto con la información relevante.
 * Responde en formato JSON.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Respuesta en JSON
include 'conexion.php'; // Conexión a la base de datos

/**
 * Consulta para obtener el ganador de cada rally (foto admitida con más votos).
 * @var string $sql
 */
$sql = "SELECT r.nombre AS nombre_rally, r.fecha_fin, f.imagen_base64, u.nombre_completo AS nombre_usuario, f.total_votos
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

/**
 * Ejecuta la consulta y verifica si fue exitosa.
 * @var mysqli_result|false $res
 */
$res = $mysqli->query($sql);
if (!$res) {
    http_response_code(500); // Error en la consulta
    echo json_encode(['error' => $mysqli->error, 'sql' => $sql]);
    $mysqli->close();
    exit;
}

/**
 * Recorre los resultados y los almacena en un array.
 * @var array $ganadores
 */
$ganadores = [];
while ($row = $res->fetch_assoc()) {
    $ganadores[] = $row; // Añade cada ganador al array
}

/**
 * Devuelve los ganadores en formato JSON.
 */
echo json_encode($ganadores);

$mysqli->close();
?>