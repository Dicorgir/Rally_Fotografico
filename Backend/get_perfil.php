<?php
header('Content-Type: application/json');
include 'conexion.php'; // Debe definir $mysqli

if (!isset($_GET['email'])) {
    echo json_encode(['error' => 'Falta el parámetro email']);
    exit;
}

$email = $_GET['email'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Formato de email no válido']);
    exit;
}

$sql = "SELECT nombre_completo, email, telefono, fecha_nacimiento, pais, genero, foto_perfil FROM usuarios WHERE email = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error en prepare: ' . $mysqli->error]);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}
$stmt->close();
$mysqli->close();
?>