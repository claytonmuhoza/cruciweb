<?php 
namespace App\Controllers;
class ErrorPageController extends BaseController {
    public function error404() {
        $this->render('error/404');
    }
}