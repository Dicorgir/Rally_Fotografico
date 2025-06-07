<?php
/**
 * listar_rallies.php
 *
 * Devuelve la lista de todos los rallies ordenados por fecha de inicio descendente.
 * Responde con un array de rallies en formato JSON.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Respuesta en JSON
require_once 'conexion.php'; // Conexión a la base de datos

/**
 * Consulta los rallies ordenados por fecha de inicio descendente.
 * @var mysqli_result|false $res
 */
$res = $mysqli->query("SELECT id_rally, nombre, fecha_inicio, fecha_fin, fecha_inicio_votacion, fecha_fin_votacion FROM rallies ORDER BY fecha_inicio DESC");

/**
 * Recorre los resultados y los almacena en un array.
 * @var array $rallies
 */
$rallies = [];
while ($row = $res->fetch_assoc()) {
    $rallies[] = $row; // Añade cada rally al array
}

/**
 * Devuelve los rallies en formato JSON.
 */
echo json_encode(['success' => true, 'rallies' => $rallies]);

$mysqli->close();
?>