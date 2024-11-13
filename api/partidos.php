<?php
require 'config.php';
require 'librerias/AltoRouter.php';

$router = new AltoRouter();
$router->setBasePath('/nfl-app');

// Obtener partidos 

$router->map('GET', '/partidos', function() use ($database){
    $games = $database->select("games", "*");

    header('Content-Type: application/json');
    echo json_encode($games);
});

$match = $router->match();

if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    // Ruta no encontrada o no válida
    header('Content-Type: application/json');
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
}

?>
