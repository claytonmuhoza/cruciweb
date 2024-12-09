<?php
namespace App\Controllers;
use App\Models\Utilisateur;
class UtilisateurController extends BaseController {
    private $utilisateurModel;

    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
    }

    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->utilisateurModel->existe($email)) {
                $this->render('inscription', ['error' => "L'adresse email existe déjà."]);
            } else if ($this->utilisateurModel->inscrire($email, $password)) {
                $this->redirect('/connexion');
            } else {
                $this->render('inscription', ['error' => "Erreur lors de l'inscription."]);
            }
        } else {
            $this->render('inscription');
        }
    }
    
    public function connexion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $utilisateur = $this->utilisateurModel->connecter($email, $password);
            if ($utilisateur) {
                $_SESSION['user'] = $utilisateur;
                $this->redirect('/grilles');
            } else {
                $this->render('connexion', ['error' => "Email ou mot de passe incorrect."]);
            }
        } else {
            $this->render('connexion');
        }
    }

    public function deconnexion() {
        session_destroy();
        $this->redirect('/');
    }

    public function supprimerUtilisateur($id) {
        if ($this->isAdmin()) {
            $this->utilisateurModel->supprimer($id);
            $this->redirect('/admin/utilisateurs');
        } else {
            $this->redirect('/');
        }
    }
}
    