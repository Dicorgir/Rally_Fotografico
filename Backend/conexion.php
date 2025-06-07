<?php
/**
 * conexion.php
 *
 * Establece la conexión a la base de datos MySQL para el sistema Rally Fotográfico.
 * Si ocurre un error, devuelve un mensaje en formato JSON y detiene la ejecución.
 *
 * PHP version 8.0.30
 *
 * @author  Diego André Cornejo Giraldo
 * @package Rally_Fotografico\Backend
 */

/**
 * Crea una nueva instancia de conexión MySQLi con los parámetros del servidor, usuario, contraseña, base de datos y puerto.
 * @var mysqli $mysqli Conexión a la base de datos MySQL
 */
$mysqli = new mysqli('localhost', 'root', '', 'rally_fotografico', 3307);
//$mysqli = new mysqli('sql306.byethost16.com', 'b16_39168921', 'ybg3ck71', 'b16_39168921_rally_fotografico', 3306);

/**
 * Comprueba si ocurrió un error al intentar conectar a la base de datos.
 * Si hay error, responde con código 500 y mensaje en JSON.
 */
if ($mysqli->connect_errno) {
    http_response_code(500); // Devuelve un código HTTP 500 (error interno del servidor)
    echo json_encode(['message' => 'Error de conexión a la base de datos']); // Devuelve un mensaje de error en formato JSON
    exit; // Termina la ejecución del script
}
?>