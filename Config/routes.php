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
// } elseif (preg_match('#^/grilles/resolve/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
//     $gridId = $matches[1];
//     (new GridController())->show($gridId); // Appelle la méthode resolve avec l'ID de la grille
} elseif ($requestUri === '/grilles/sauvegarde' && $requestMethod === 'GET') {
    (new GridController())->showAllSavedGrid();
} elseif (preg_match('#^/grilles/resolve/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $gridId = $matches[1];
    (new GridController())->resolveGrid($gridId); // Appelle la méthode resolve avec l'ID de la grille
} elseif (preg_match('#^/grilles/delete/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $gridId = $matches[1];
    (new GridController())->deleteGrid($gridId); // Appelle la méthode deleteGrid avec l'ID de la grille
}elseif ($requestUri === '/grilles/save-progress' && $requestMethod === 'POST') {
    (new GridController())->save(); // Sauvegarde la progression
}elseif ($requestUri === '/api/grids' && $requestMethod === 'GET') {
    (new GridController())->getAllGridsJson();
}elseif ($requestUri === '/api/grids/sauvegarder-grid' && $requestMethod === 'POST') {
    (new GridController())->save();
} elseif (preg_match('#^/api/grids/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $gridId = $matches[1];
    (new GridController())->getGridJson($gridId);
} elseif (preg_match('#^/api/grids/(\d+)/definitions$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $gridId = $matches[1];
    (new GridController())->getDefinitionsJson($gridId);
} elseif (preg_match('#^/api/grids/(\d+)/cells$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $gridId = $matches[1];
    (new GridController())->getCellsJson($gridId);
}elseif (preg_match('#^/api/grids/verification/(\d+)$#', $requestUri, $matches) && $requestMethod === 'POST') {
    $gridId = $matches[1];
    (new GridController())->verificationCellsJSON($gridId);
}elseif ($requestUri === '/admin/utilisateurs' && $requestMethod === 'GET') {
    (new UtilisateurController())->showAllUser(); 
}elseif ($requestUri === '/admin/grilles' && $requestMethod === 'GET') {
    (new GridController())->liste(); 
}elseif (preg_match('#^/grilles/resolve/(\d+)$#', $requestUri, $matches) && $requestMethod === 'GET') {
    $gridId = $matches[1];
    (new GridController())->resolveGrid($gridId); // Appelle la méthode resolve avec l'ID de la grille
} elseif ($requestUri === '/deconnexion') {
    (new UtilisateurController())->deconnexion();
} else {
    http_response_code(404);
    (new ErrorPageController())->error404();
}

