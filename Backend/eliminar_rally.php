<?php
/**
 * eliminar_rally.php
 *
 * Elimina un rally de la base de datos a partir de su ID recibido por POST.
 * Devuelve una respuesta JSON indicando el resultado de la operación.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

ini_set('display_errors', 1); // Activa la visualización de errores en tiempo de ejecución
ini_set('display_startup_errors', 1); // Activa la visualización de errores al iniciar PHP
error_reporting(E_ALL); // Muestra todos los errores de PHP

header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
require_once 'conexion.php'; // Incluye el archivo de conexión a la base de datos

/**
 * Verifica que la petición sea de tipo POST.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Obtiene el id del rally desde POST o null si no existe.
     * @var int|null $id_rally
     */
    $id_rally = $_POST['id_rally'] ?? null;

    /**
     * Valida que se haya proporcionado el id del rally.
     */
    if (!$id_rally) {
        http_response_code(400); // Código HTTP 400: petición incorrecta
        echo json_encode(['success' => false, 'message' => 'ID de rally no proporcionado']);
        exit;
    }

    /**
     * Prepara y ejecuta la consulta de eliminación del rally.
     */
    $stmt = $mysqli->prepare("DELETE FROM rallies WHERE id_rally = ?");
    $stmt->bind_param("i", $id_rally);

    /**
     * Si la eliminación fue exitosa, responde con éxito.
     */
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Rally eliminado correctamente']);
    } else {
        // Si hubo un error al eliminar, responde con código 500 y mensaje de error
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el rally']);
    }

    $stmt->close(); // Cierra la consulta preparada
    $mysqli->close(); // Cierra la conexión a la base de datos
} else {
    // Si la petición no es POST, responde con código 405 (método no permitido)
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>