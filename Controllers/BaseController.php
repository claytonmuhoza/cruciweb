<?php
namespace App\Controllers;
use App\Models\DatabaseInitializer;
class BaseController {
    public function __construct() {
        DatabaseInitializer::initialize();
    }
    protected function render($view, $data = []) {
        extract($data);
        include __DIR__ . "/../Views/" . $view . ".php";
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function isAuthenticated() {
        return isset($_SESSION['user']);
    }

    protected function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
    //Methode qui permet de mettre une lettre en majuscule si different de null si vide retourne vide
    protected function capitalize($letter) {
        return $letter ? strtoupper($letter) : null;
    }
}
