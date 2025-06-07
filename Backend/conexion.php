<?php
// Crea una nueva instancia de conexión MySQLi con los parámetros del servidor, usuario, contraseña, base de datos y puerto
$mysqli = new mysqli('localhost', 'root', '', 'rally_fotografico', 3307);
//$mysqli = new mysqli('sql306.byethost16.com', 'b16_39168921', 'ybg3ck71', 'b16_39168921_rally_fotografico', 3306);


// Comprueba si ocurrió un error al intentar conectar a la base de datos
if ($mysqli->connect_errno) {
    http_response_code(500); // Devuelve un código HTTP 500 (error interno del servidor)
    echo json_encode(['message' => 'Error de conexión a la base de datos']); // Devuelve un mensaje de error en formato JSON
    exit; // Termina la ejecución del script
}
?>