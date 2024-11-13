<?php
// Cargar las bibliotecas de Medoo y cualquier otra biblioteca
require 'librerias/Medoo.php';

use Medoo\Medoo;

// Configuración de la base de datos
$database = new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',            // Host de la base de datos (normalmente 'localhost')
    'database' => 'nfl_db',           // Nombre de la base de datos
    'username' => 'root',             // Usuario de la base de datos
    'password' => '#America1496',    // Contraseña de la base de datos
    'charset' => 'utf8mb4'            // Configuración de caracteres para UTF-8 completo
]);

// Configuración general
define('APP_NAME', 'NFL App');         // Nombre de la aplicación, por ejemplo
define('BASE_URL', 'http://localhost'); // URL base para construir enlaces si es necesario

?>
