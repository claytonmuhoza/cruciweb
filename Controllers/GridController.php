<?php
namespace App\Controllers;

use App\Models\Grid;
use App\Models\Definition;
use App\Models\Cell;
use App\Models\Sauvegarde;

class GridController extends BaseController
{
    /**
     * Affiche le formulaire de création de grille.
     */
    public function create()
    {
        $this->render('grids/create', ['title' => 'Créer une Grille']);
    }

    /**
     * Gère la soumission du formulaire de création de grille.
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $name = $_POST['name'] ?? '';
            $difficulty = $_POST['difficulty'] ?? 'beginner';
            $rows = (int) ($_POST['rows'] ?? 0);
            $columns = (int) ($_POST['columns'] ?? 0);
            $definitions = $_POST['definitions'] ?? [];
            $cells = $_POST['cells'] ?? [];

            // Validation des données
            if (empty($name) || $rows <= 0 || $columns <= 0) {
                $this->render('grids/create', [
                    'title' => 'Créer une Grille',
                    'error' => 'Veuillez remplir tous les champs requis.',
                ]);
                return;
            }

            // Préparer les données pour l'enregistrement
            $gridData = [
                'name' => htmlspecialchars($name),
                'difficulty' => htmlspecialchars($difficulty),
                'user_id' => $_SESSION['user']['id'], // Assurez-vous que l'utilisateur est connecté
            ];

            // Enregistrer la grille
            $gridId = Grid::save($gridData);

            if ($gridId === null) {
                $this->render('grids/create', [
                    'title' => 'Créer une Grille',
                    'error' => 'Erreur lors de l\'enregistrement de la grille.',
                ]);
                return;
            }

            // Enregistrer les définitions
            if (!Grid::addDefinitions($gridId, $definitions)) {
                $this->render('grids/create', [
                    'title' => 'Créer une Grille',
                    'error' => 'Erreur lors de l\'ajout des définitions.',
                ]);
                return;
            }

            // Enregistrer les cellules
            if (!Grid::addCells($gridId, $cells)) {
                $this->render('grids/create', [
                    'title' => 'Créer une Grille',
                    'error' => 'Erreur lors de l\'ajout des cellules.',
                ]);
                return;
            }

            // Rediriger vers la liste des grilles avec un message de succès
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'La grille a été créée avec succès.',
            ];
            $this->redirect('/grilles');
        } else {
            // Si la méthode n'est pas POST, rediriger vers le formulaire de création
            $this->redirect('/grilles/create');
        }
    }

    /**
     * Affiche la liste des grilles.
     */
    public function index()
    {
        $grids = Grid::getAll();
        $this->render('grids/liste', [
            'title' => 'Accueil- CruciWeb',
            'grids' => $grids,
        ]);
    }
    public function liste()
    {
        $grids = Grid::getAll();
        $this->render('grids/liste', [
            'title' => 'Liste des Grilles',
            'grids' => $grids,
        ]);
    }

    /**
     * Supprime une grille par son ID.
     *
     * @param int $id ID de la grille à supprimer.
     */
    public function delete($id)
    {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            $this->redirect('/');
            return;
        }

        if (Grid::delete((int) $id)) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'La grille a été supprimée avec succès.',
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Erreur lors de la suppression de la grille.',
            ];
        }

        $this->redirect('/grids');
    }
    public function show($gridId)
    {
        $grid = Grid::getById($gridId);
        $definitions = Definition::getByGridId($gridId);
        $cells = Cell::getByGridId($gridId);

        if (!$grid) {
            $this->render('error/404', ['title' => 'Grille introuvable']);
            return;
        }

        $this->render('grids/resolve', [
            'title' => 'Résolution de la Grille',
            'grid' => $grid,
            'definitions' => $definitions,
            'cells' => $cells,
        ]);
    }

    /**
     * Sauvegarde l'état actuel de la grille pour un utilisateur connecté.
     */
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $gridId = $_POST['grid_id'] ?? null;
            $gridState = $_POST['grid_state'] ?? null;

            if (!$gridId || !$gridState) {
                http_response_code(400);
                echo json_encode(['error' => 'Données invalides']);
                return;
            }

            $sauvegarde = new Sauvegarde();
            $success = $sauvegarde->sauvegarderGrille($userId, $gridId, $gridState);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erreur lors de la sauvegarde']);
            }
        }
    }
}
