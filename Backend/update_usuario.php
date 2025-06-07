<?php
/**
 * update_usuario.php
 *
 * Actualiza los datos de un usuario en la base de datos.
 * Recibe los datos por POST, valida los campos y actualiza la información.
 * Devuelve una respuesta JSON indicando el resultado de la operación.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json'); // Respuesta en JSON
include 'conexion.php'; // Conexión a la base de datos

/**
 * Recoge los datos enviados por POST.
 * @var int|string $id_usuario
 * @var string $nombre
 * @var string $email
 * @var string|null $telefono
 * @var string|null $pais
 * @var string|null $genero
 */
$id_usuario = $_POST['id_usuario'] ?? '';
$nombre = $_POST['nombre_completo'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$pais = $_POST['pais'] ?? '';
$genero = $_POST['genero'] ?? '';

/**
 * Validaciones básicas de los campos recibidos.
 */
if (!$id_usuario || !$nombre || !$email) {
    http_response_code(400);
    echo json_encode(['message' => 'Faltan datos obligatorios']);
    exit;
}

if (!is_numeric($id_usuario)) {
    http_response_code(400);
    echo json_encode(['message' => 'ID de usuario inválido']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'El correo no es válido']);
    exit;
}

if ($pais !== null && strlen($pais) > 50) {
    http_response_code(400);
    echo json_encode(['message' => 'El país no puede tener más de 50 caracteres']);
    exit;
}

/**
 * Actualiza los datos del usuario en la base de datos.
 */
$sql = "UPDATE usuarios SET nombre_completo=?, email=?, telefono=?, pais=?, genero=? WHERE id_usuario=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssssi", $nombre, $email, $telefono, $pais, $genero, $id_usuario);

if ($stmt->execute()) {
    echo json_encode(['message' => 'USUARIO ACTUALIZADO CORRECTAMENTE']);
} else {
    http_response_code(500);
    echo json_encode(['message' => 'ERROR AL ACTUALIZAR EL USUARIO']);
}
$stmt->close();
$mysqli->close();
?>