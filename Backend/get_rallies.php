<?php
/**
 * get_rallies.php
 *
 * Devuelve todos los rallies registrados en la base de datos.
 * Responde con un array de rallies en formato JSON.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Respuesta en JSON
include 'conexion.php'; // Conexión a la base de datos

/**
 * Consulta todos los rallies registrados.
 * @var string $sql
 * @var mysqli_result|false $result
 */
$sql = "SELECT * FROM rallies";
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500); // Error en la consulta
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

/**
 * Recorre los resultados y los almacena en un array.
 * @var array $rallies
 */
$rallies = [];
while ($row = $result->fetch_assoc()) {
    $rallies[] = $row; // Añade cada rally al array
}

/**
 * Devuelve el array de rallies en formato JSON.
 */
echo json_encode($rallies);

$mysqli->close();
?>