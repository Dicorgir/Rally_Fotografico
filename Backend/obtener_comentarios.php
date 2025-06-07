<?php
/**
 * obtener_comentarios.php
 *
 * Devuelve los comentarios de una fotografía específica.
 * Recibe el id de la fotografía por GET.
 * Responde con un array de comentarios en formato JSON.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Respuesta en JSON
require_once 'conexion.php'; // Conexión a la base de datos

/**
 * Obtiene el id de la fotografía desde GET.
 * @var int $id_fotografia
 */
$id_fotografia = isset($_GET['id_fotografia']) ? intval($_GET['id_fotografia']) : 0;

if ($id_fotografia <= 0) {
    echo json_encode(['success' => false, 'comentarios' => []]); // Si no es válido, responde vacío
    exit;
}

/**
 * Consulta los comentarios de la fotografía.
 * @var mysqli_stmt $res
 * @var mysqli_result $result
 */
$res = $mysqli->prepare("SELECT comentario, fecha_comentario FROM comentarios WHERE id_fotografia = ? ORDER BY fecha_comentario DESC");
$res->bind_param("i", $id_fotografia);
$res->execute();
$result = $res->get_result();

/**
 * Recorre los resultados y los almacena en un array.
 * @var array $comentarios
 */
$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row; // Añade cada comentario al array
}

/**
 * Devuelve los comentarios en formato JSON.
 */
echo json_encode(['success' => true, 'comentarios' => $comentarios]);

$res->close();
$mysqli->close();
?>