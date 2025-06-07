<?php
/**
 * rallies_todos.php
 *
 * Devuelve todos los rallies ordenados por fecha de inicio ascendente.
 * Responde con un array de rallies en formato JSON y fuerza la codificación UTF-8.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json; charset=utf-8');
require_once 'conexion.php';

/**
 * Consulta todos los rallies ordenados por fecha de inicio ascendente.
 * @var mysqli_result|false $res
 */
$res = $mysqli->query("SELECT id_rally, nombre, fecha_inicio, fecha_fin FROM rallies ORDER BY fecha_inicio ASC");
if (!$res) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en la consulta: ' . $mysqli->error]);
    exit;
}

/**
 * Recorre los resultados, fuerza UTF-8 en cada campo y los almacena en un array.
 * @var array $rallies
 */
$rallies = [];
while ($row = $res->fetch_assoc()) {
    // Fuerza UTF-8 en cada campo
    foreach ($row as $key => $value) {
        $row[$key] = $value !== null ? mb_convert_encoding($value, 'UTF-8', 'auto') : null;
    }
    $rallies[] = $row;
}

/**
 * Devuelve el array de rallies en formato JSON.
 */
echo json_encode(['success' => true, 'rallies' => $rallies], JSON_UNESCAPED_UNICODE);
?>