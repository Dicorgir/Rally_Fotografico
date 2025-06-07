<?php
/**
 * get_usuarios.php
 *
 * Devuelve todos los usuarios registrados en la base de datos con información básica.
 * Responde con un array de usuarios en formato JSON.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Respuesta en JSON
include 'conexion.php'; // Conexión a la base de datos

/**
 * Consulta para obtener los usuarios registrados.
 * @var string $sql
 * @var mysqli_result|false $result
 */
$sql = "SELECT id_usuario, nombre_completo, email, telefono, pais, genero, foto_perfil FROM usuarios";
$result = $mysqli->query($sql);

if (!$result) {
    http_response_code(500); // Error en la consulta
    echo json_encode(['error' => 'Error en la consulta: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

/**
 * Recorre los resultados y los almacena en un array.
 * @var array $usuarios
 */
$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row; // Añade cada usuario al array
}

/**
 * Devuelve el array de usuarios en formato JSON.
 */
echo json_encode($usuarios);

$mysqli->close();
?>