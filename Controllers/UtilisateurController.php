<?php
namespace App\Controllers;

use App\Models\Sauvegarde;
use App\Models\Utilisateur;
class UtilisateurController extends BaseController {
    private $utilisateurModel;

    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
    }

    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->utilisateurModel->existe($username)) {
                $this->render('auth/inscription', ['error' => "Le nom d'utilisateur existe déjà."]);
            } else if ($this->utilisateurModel->inscrire($username, $password)) {
                $this->connexion();
            } else {
                $this->render('auth/inscription', ['error' => "Erreur lors de l'inscription."]);
            }
        } else {
            $this->render('auth/inscription');
        }
    }
    
    public function connexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $utilisateur = $this->utilisateurModel->connecter($username, $password);
            if ($utilisateur) {
                $_SESSION['user'] = $utilisateur;
                $this->redirect('/grilles');
            } else {
                $this->render('auth/connexion', ['error' => "Nom d'utilisateur ou mot de passe incorrect."]);
            }
        } else {
            $this->render('auth/connexion');
        }
    }

    public function deconnexion() {
        session_destroy();
        $this->redirect('/');
    }
    public function deleteUser($id)
    {
        if ($this->isAdmin()) {
            $sauvegarde = new Sauvegarde();
            $sauvegarde->deleteByUser($id);
            $utilisateur = new Utilisateur();
            $utilisateur->supprimer($id);
            $this->redirect('/admin/utilisateurs');
        } else {
            $this->render('error/errorpage', ['codeErreur' => 403, 'messageErreur' => 'Vous n\'avez pas les droits pour accéder à cette page']);
            return;
        }
    }
    public function showAllUser()
    {
        if($this->isAdmin()){
            $utilisateur = new Utilisateur();
            $users = $utilisateur->afficherTous();
            $this->render('admin/utilisateurs', ['users' => $users]);
        }else{
            $this->render('error/errorpage', ['codeErreur' => 403, 'messageErreur' => 'Vous n\'avez pas les droits pour accéder à cette page']);
            return;
        }
    }
   

    
}
    