<?php
/**
 * update_perfil.php
 *
 * Actualiza el perfil de un usuario en la base de datos.
 * Recibe los datos del usuario por POST, valida los campos y actualiza la información.
 * Permite actualizar la foto de perfil.
 * Devuelve una respuesta JSON indicando el resultado de la operación.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

header('Content-Type: application/json');
include 'conexion.php';

// Recoge los datos enviados por POST
/**
 * @var string $nombre_completo
 * @var string $email
 * @var string|null $telefono
 * @var string|null $fecha_nacimiento
 * @var string|null $pais
 * @var string|null $genero
 */
$nombre_completo = $_POST['nombre_completo'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? null;
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
$pais = $_POST['pais'] ?? null;
$genero = $_POST['genero'] ?? null;

// Procesa la foto de perfil si se subió una nueva
/**
 * @var string|null $foto_perfil
 */
$foto_perfil = null;
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid('perfil_') . '.' . $ext;
    $ruta_destino = __DIR__ . '/uploads/' . $nombre_archivo;
    if (!is_dir(__DIR__ . '/uploads')) {
        mkdir(__DIR__ . '/uploads', 0777, true);
    }
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_destino)) {
        $foto_perfil = 'uploads/' . $nombre_archivo;
    }
} else {
    // Si no se subió una nueva, mantiene la foto anterior
    $stmt = $mysqli->prepare("SELECT foto_perfil FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($foto_perfil_actual);
    if ($stmt->fetch()) {
        $foto_perfil = $foto_perfil_actual;
    }
    $stmt->close();
}

// Validaciones básicas
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
if (!$email || !$nombre_completo) {
    http_response_code(400);
    echo json_encode(['message' => 'Faltan datos obligatorios']);
    exit;
}

/**
 * Actualiza los datos del usuario en la base de datos.
 */
$sql = "UPDATE usuarios SET nombre_completo=?, telefono=?, fecha_nacimiento=?, pais=?, genero=?, foto_perfil=? WHERE email=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param(
    "sssssss",
    $nombre_completo,
    $telefono,
    $fecha_nacimiento,
    $pais,
    $genero,
    $foto_perfil,
    $email
);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Perfil actualizado correctamente']);
} else {
    echo json_encode(['message' => 'Error al actualizar el perfil']);
}
$stmt->close();
$mysqli->close();
?>