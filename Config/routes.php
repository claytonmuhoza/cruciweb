<?php
namespace App\Config;
require_once __DIR__ . '/../Autoloader.php';

use App\Controllers\UtilisateurController;
use App\Controllers\ErrorPageController;
use App\Controllers\GridController;

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/' && $requestMethod === 'GET') {
    (new GridController())->index();
} elseif ($requestUri === '/connexion') {
    (new UtilisateurController())->connexion();
} elseif ($requestUri === '/inscription') {
    (new UtilisateurController())->inscription();
} elseif ($requestUri === '/grilles/creation' && $requestMethod === 'GET') {
    (new GridController())->create();
} elseif ($requestUri === '/grilles/store' && $requestMethod === 'POST') {
    (new GridController())->store();
} elseif ($requestUri === '/grilles' && $requestMethod === 'GET') {
    (new GridController())->liste();
} elseif (preg_match('#^/grilles/resolve/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $gridId = $matches[1];
    (new GridController())->show($gridId); // Appelle la méthode resolve avec l'ID de la grille
} elseif ($requestUri === '/grilles/save-progress' && $requestMethod === 'POST') {
    (new GridController())->save(); // Sauvegarde la progression
} elseif ($requestUri === '/deconnexion') {
    (new UtilisateurController())->deconnexion();
} else {
    http_response_code(404);
    (new ErrorPageController())->error404();
}
