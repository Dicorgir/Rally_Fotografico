<?php
header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON
include 'conexion.php'; // Incluye la conexión a la base de datos

// Realiza una consulta para contar el total de usuarios registrados
$total_usuarios = $mysqli->query("SELECT COUNT(*) FROM usuarios");
// Realiza una consulta para contar el total de rallies creados
$total_rallies = $mysqli->query("SELECT COUNT(*) FROM rallies");
// Realiza una consulta para contar el total de fotografías subidas
$total_fotos = $mysqli->query("SELECT COUNT(*) FROM fotografias");
// Cuenta las fotografías admitidas
$fotos_admitidas = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='admitida'");
// Cuenta las fotografías rechazadas
$fotos_rechazadas = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='rechazada'");
// Cuenta las fotografías pendientes de revisión
$fotos_pendientes = $mysqli->query("SELECT COUNT(*) FROM fotografias WHERE estado='pendiente'");

// Verifica si alguna de las consultas falló
if (
    !$total_usuarios || !$total_rallies || !$total_fotos ||
    !$fotos_admitidas || !$fotos_rechazadas || !$fotos_pendientes
) {
    http_response_code(500); // Código HTTP 500: error interno del servidor
    echo json_encode(['error' => 'Error en la consulta de estadísticas']); // Devuelve mensaje de error en JSON
    $mysqli->close(); // Cierra la conexión a la base de datos
    exit;
}

// Devuelve los resultados de las consultas en formato JSON
echo json_encode([
    'total_usuarios' => $total_usuarios->fetch_row()[0], // Total de usuarios
    'total_rallies' => $total_rallies->fetch_row()[0], // Total de rallies
    'total_fotos' => $total_fotos->fetch_row()[0], // Total de fotos
    'fotos_admitidas' => $fotos_admitidas->fetch_row()[0], // Fotos admitidas
    'fotos_rechazadas' => $fotos_rechazadas->fetch_row()[0], // Fotos rechazadas
    'fotos_pendientes' => $fotos_pendientes->fetch_row()[0] // Fotos pendientes
]);
$mysqli->close(); // Cierra la conexión a la base de datos
?>