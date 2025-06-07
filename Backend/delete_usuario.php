<?php
/**
 * delete_usuario.php
 *
 * Elimina un usuario de la base de datos a partir de su ID recibido por POST.
 * Devuelve una respuesta JSON indicando el resultado de la operación.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
include 'conexion.php'; // Incluye la conexión a la base de datos

/**
 * Obtiene el id del usuario desde POST o una cadena vacía si no existe.
 * @var string|int $id_usuario
 */
$id_usuario = $_POST['id_usuario'] ?? '';

/**
 * Valida que el id_usuario esté presente y sea numérico.
 */
if (!$id_usuario || !is_numeric($id_usuario)) {
    http_response_code(400); // Código HTTP 400: petición incorrecta
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido']);
    exit;
}

/**
 * Prepara la consulta SQL para eliminar el usuario con el id proporcionado.
 * @var string $sql
 * @var mysqli_stmt $stmt
 */
$sql = "DELETE FROM usuarios WHERE id_usuario=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);

/**
 * Ejecuta la consulta y verifica si fue exitosa.
 */
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
} else {
    http_response_code(500); // Código HTTP 500: error interno del servidor
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
}

/**
 * Cierra la consulta preparada y la conexión a la base de datos.
 */
$stmt->close();
$mysqli->close();
?>