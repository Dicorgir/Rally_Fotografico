<?php
/**
 * get_fotos_pendientes.php
 *
 * Devuelve todas las fotos pendientes, admitidas y rechazadas, junto con información del usuario y del rally.
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
 * Consulta para obtener fotos pendientes, admitidas y rechazadas, junto con usuario y rally.
 * @var string $sql
 */
$sql = "SELECT 
            f.id_fotografia, 
            f.imagen_base64, 
            f.estado, 
            u.nombre_completo AS nombre_usuario, 
            r.nombre AS nombre_rally
        FROM fotografias f
        JOIN usuarios u ON f.id_usuario = u.id_usuario
        JOIN rallies r ON f.id_rally = r.id_rally
        WHERE f.estado IN ('pendiente', 'admitida', 'rechazada')";

/**
 * Ejecuta la consulta y verifica si fue exitosa.
 * @var mysqli_result|false $result
 */
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500); // Error en la consulta
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

/**
 * Recorre los resultados y los almacena en un array.
 * @var array $fotos
 */
$fotos = [];
while ($row = $result->fetch_assoc()) {
    $fotos[] = $row; // Añade cada foto al array
}

/**
 * Devuelve el array de fotos en formato JSON.
 */
echo json_encode($fotos);

$mysqli->close();
?>