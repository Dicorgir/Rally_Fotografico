<?php
header('Content-Type: application/json');
include 'conexion.php';

$nombre_completo = $_POST['nombre_completo'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? null;
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
$pais = $_POST['pais'] ?? null;
$genero = $_POST['genero'] ?? null;

// Procesar la foto de perfil si se subió
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
    // Mantener la foto anterior si no se subió una nueva
    $stmt = $mysqli->prepare("SELECT foto_perfil FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($foto_perfil_actual);
    if ($stmt->fetch()) {
        $foto_perfil = $foto_perfil_actual;
    }
    $stmt->close();
}

// Validar formato de correo
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['message' => 'El correo no es válido']);
    exit;
}

// Validar longitud del país
if ($pais !== null && strlen($pais) > 50) {
    echo json_encode(['message' => 'El país no puede tener más de 50 caracteres']);
    exit;
}

if (!$email || !$nombre_completo) {
    echo json_encode(['message' => 'Faltan datos obligatorios']);
    exit;
}

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