<?php
header('Content-Type: application/json');
include 'conexion.php';

$sql = "SELECT * FROM rallies";
$result = $mysqli->query($sql);
$rallies = [];
while ($row = $result->fetch_assoc()) {
    $rallies[] = $row;
}
echo json_encode($rallies);
$mysqli->close();
?>