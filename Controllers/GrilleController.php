<?php
namespace App\Controllers;
use App\Models\Grille;
class GrilleController extends BaseController {
    private $grilleModel;

    public function __construct() {
        $this->grilleModel = new Grille();
    }
    public function index()
    {
        $grilles = $this->grilleModel->listerGrilles();                         
        $this->render('grilles/liste', ['grilles' => $grilles]);
    }
    public function liste() {
        $grilles = $this->grilleModel->listerGrilles();
        $this->render('grilles/liste', ['grilles' => $grilles]);
    }

    public function creerGrille() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/connexion');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $dimensions = $_POST['dimensions'];
            $niveau = $_POST['niveau'];
            $cases_noires = json_decode($_POST['cases_noires'], true);
            $definitions = json_decode($_POST['definitions'], true);
            $solution = $_POST['solution'];
            $utilisateur_id = $_SESSION['user']['id'];

            if ($this->grilleModel->creerGrille($nom, $dimensions, $niveau, $cases_noires, $definitions, $solution, $utilisateur_id)) {
                $this->redirect('/grilles');
            } else {
                $this->render('grilles/creation', ['error' => "Erreur lors de la création de la grille."]);
            }
        } else {
            $this->render('grilles/creation');
        }
    }

    public function supprimerGrille($id) {
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }

        if ($this->grilleModel->supprimerGrille($id)) {
            $this->redirect('/grilles');
        } else {
            $this->render('grilles/liste', ['error' => "Erreur lors de la suppression de la grille."]);
        }
    }
}
