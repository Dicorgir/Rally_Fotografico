<?php
// Indica que la respuesta será en formato JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
include 'conexion.php';

// Obtiene el id de la fotografía desde la petición POST, o una cadena vacía si no existe
$id_fotografia = $_POST['id_fotografia'] ?? '';

// Obtiene el nuevo estado desde la petición POST, o una cadena vacía si no existe
$estado = $_POST['estado'] ?? '';

// Valida que el estado recibido sea uno de los permitidos
if (!in_array($estado, ['pendiente', 'admitida', 'rechazada'])) {
    http_response_code(400); // Código HTTP 400: petición incorrecta
    echo json_encode(['message' => 'Estado no válido']); // Devuelve mensaje de error en JSON
    exit; // Termina la ejecución del script
}

// Prepara la consulta SQL para actualizar el estado de la fotografía
$sql = "UPDATE fotografias SET estado=? WHERE id_fotografia=?";
$stmt = $mysqli->prepare($sql);

// Asocia los parámetros recibidos a la consulta preparada
$stmt->bind_param("si", $estado, $id_fotografia);

// Ejecuta la consulta y verifica si fue exitosa
if ($stmt->execute()) {
    // Si la actualización fue exitosa, devuelve un mensaje de éxito en JSON
    echo json_encode(['message' => 'ESTADO ACTUALIZADO CORRECTAMENTE']);
} else {
    // Si hubo un error en la actualización, responde con código 500 y mensaje de error
    http_response_code(500);
    echo json_encode(['message' => 'ERROR AL ACTUALIZAR EL ESTADO']);
}

// Cierra la consulta preparada
$stmt->close();
// Cierra la conexión a la base de datos
$mysqli->close();
?>