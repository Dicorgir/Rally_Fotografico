<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once 'conexion.php'; // Aquí se define $mysqli

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_rally = $_POST['id_rally'] ?? null;

    if (!$id_rally) {
        echo json_encode(['success' => false, 'message' => 'ID de rally no proporcionado']);
        exit;
    }

    // Prepara y ejecuta la consulta de eliminación
    $stmt = $mysqli->prepare("DELETE FROM rallies WHERE id_rally = ?");
    $stmt->bind_param("i", $id_rally);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Rally eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el rally']);
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>