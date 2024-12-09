<?php
namespace App\Config;
require_once __DIR__ . '/../Autoloader.php';
// use App\Controllers\UserController;
use App\Controllers\GrilleController;
use App\Controllers\UtilisateurController;
use App\Controllers\ErrorPageController;
use App\Controllers\GridController;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/' && $requestMethod === 'GET') {
    (new GrilleController())->index();
} elseif ($requestUri === '/connexion') {
    (new UtilisateurController())->connexion();
}elseif ($requestUri === '/inscription') {
    (new UtilisateurController())->inscription();
}elseif ($requestUri === '/grilles/creation' && $requestMethod === 'GET') {
    (new GridController())->create();
} 
elseif ($requestUri === '/grilles/creation' && $requestMethod === 'POST') {
    (new GridController())->store();
} elseif ($requestUri === '/grilles' && $requestMethod === 'GET') {
    (new GrilleController())->liste();
} else if ($requestUri==='/deconnexion'){
    (new UtilisateurController())->deconnexion();
}else {
    http_response_code(404);
    (new ErrorPageController())->error404();
}
