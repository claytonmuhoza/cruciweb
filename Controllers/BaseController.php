<?php
namespace App\Controllers;
class BaseController {
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
}
