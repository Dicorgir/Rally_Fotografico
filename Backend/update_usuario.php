<?php
header('Content-Type: application/json');
include 'conexion.php';

$id_usuario = $_POST['id_usuario'] ?? '';
$nombre = $_POST['nombre_completo'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$pais = $_POST['pais'] ?? '';
$genero = $_POST['genero'] ?? '';

if (!$id_usuario || !$nombre || !$email) {
    echo json_encode(['message' => 'Faltan datos obligatorios']);
    exit;
}

if (!is_numeric($id_usuario)) {
    echo json_encode(['message' => 'ID de usuario inválido']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['message' => 'El correo no es válido']);
    exit;
}

if ($pais !== null && strlen($pais) > 50) {
    echo json_encode(['message' => 'El país no puede tener más de 50 caracteres']);
    exit;
}

$sql = "UPDATE usuarios SET nombre_completo=?, email=?, telefono=?, pais=?, genero=? WHERE id_usuario=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssssi", $nombre, $email, $telefono, $pais, $genero, $id_usuario);

if ($stmt->execute()) {
    echo json_encode(['message' => 'USUARIO ACTUALIZADO CORRECTAMENTE']);
} else {
    echo json_encode(['message' => 'ERROR AL ACTUALIZAR EL USUARIO']);
}
$stmt->close();
$mysqli->close();
?>