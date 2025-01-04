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
        if($this->isAdmin() || !isset($_SESSION['user']))
        {
            $this->render('error/errorpage', ['codeErreur' => 403, 'messageErreur' => 'Vous n\'avez pas les droits pour accéder à cette page']);
            return;
        }
        $this->render('grids/create', ['title' => 'Créer une Grille']);
    }

    /**
     * Gère la soumission du formulaire de création de grille.
     */
    public function store()
    {
        if($this->isAdmin() || !isset($_SESSION['user']))
        {
            $this->render('error/errorpage', ['codeErreur' => 403, 'messageErreur' => 'Vous n\'avez pas les droits pour accéder à cette page']);
            return;
        }
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
                    'page_creergrille' => true,
                    'error' => 'Le nom, le nombre de lignes et de colonnes sont requis.',
                ]);
                return;
            }

            if (empty($definitions) || (count($definitions['rows']) + count($definitions['columns'])) < $rows + $columns) {

                $this->render('grids/create', [
                    'title' => 'Créer une Grille',
                    'error' => 'Toutes les définitions doivent être remplies.',
                ]);
                return;
            }

            // Vérifier que chaque ligne a au moins deux cellules remplies
            foreach ($cells as $rowIndex => $row) {
                if (count(array_filter($row)) < 2) {
                    $countRowToDisplay = $rowIndex + 1;
                    $this->render('grids/create', [
                        'title' => 'Créer une Grille',
                        'error' => "La ligne {$countRowToDisplay} doit avoir au moins deux cellules remplies.",
                    ]);
                    return;
                }
            }

            // Vérifier que chaque colonne a au moins deux cellules remplies
            for ($colIndex = 0; $colIndex < $columns; $colIndex++) {
                $filledCount = 0;
                foreach ($cells as $row) {
                    if (!empty($row[$colIndex])) {
                        $filledCount++;
                    }
                }
                if ($filledCount < 2) {
                    $countColToDisplay = $colIndex + 1;
                    $this->render('grids/create', [
                        'title' => 'Créer une Grille',
                        'error' => "La colonne {$countColToDisplay} doit avoir au moins deux cellules remplies.",
                    ]);
                    return;
                }
            }

            // Préparer les données pour l'enregistrement
            $gridData = [
                'name' => htmlspecialchars($name),
                'difficulty' => htmlspecialchars($difficulty),
            ];

            // Enregistrer la grille
            $gridId = Grid::save($gridData);

            if ($gridId === null) {
                $this->render('grids/create', [
                    'title' => 'Créer une Grille',
                    'error' => 'Erreur lors de l\'enregistrement de la grille. Une grille portant le même nom existe déjà',
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
            'homepage' => true,
            'isAdmin' => $this->isAdmin(),
            'grids' => $grids,
        ]);
    }
    public function liste()
    {
        $grids = Grid::getAll();
        $this->render('grids/liste', [
            'title' => 'Liste des Grilles',
            'isAdmin' => $this->isAdmin(),
            'grids' => $grids,
        ]);
    }

    /**
     * Supprime une grille par son ID.
     *
     * @param int $id ID de la grille à supprimer.
     */
    public function deleteGrid($id)
    {
        if ($this->isAdmin()) {
            $sauvegarde = new Sauvegarde();
            $sauvegarde->deleteByGrid($id);
            Definition::deleteByGridId($id);
            Cell::deleteByGridId($id);
            Grid::delete($id);
            $this->redirect('/admin/grilles');
        } else {
            $this->render('error/errorpage', ['codeErreur' => 403, 'messageErreur' => 'Vous n\'avez pas les droits pour accéder à cette page']);
            return;
        }
    }
    public function deleteGridByUser($id)
    {
        if($this->isAuthenticated())
        {
            $sauvegarde = new Sauvegarde();
            if($sauvegarde->sauvegardeExiste($_SESSION['user']['id'], $id))
            {
                $sauvegarde->deleteByGridAndUser($id, $_SESSION['user']['id']);
                $this->redirect('/grilles/sauvegarde');
            }
            else
            {
                echo json_encode(['success' => false]);
                return;
            }
        }
        else
        {
            echo json_encode(['success' => false]);
            return;
        }
    }
    public function resolveGrid($gridId)
    {
        
        $grid = Grid::getById($gridId);
        $definitions = Definition::getByGridId($gridId);
        $cells = Cell::getByGridId($gridId);

        if (!$grid) {
            $this->render('error/404', ['title' => 'Grille introuvable']);
            return;
        }

        $this->render('grids/resolver', [
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
        if($this->isAdmin() || !isset($_SESSION['user']))
        {
            echo json_encode(['Error' => 'Vous n\'avez pas les droits de sauvegarder la grille']);
            return;
        }
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
        else
        {
            http_response_code(401);
            echo json_encode(['error' => 'Utilisateur non connecté']);
        }
    }
    public function showAllSavedGrid()
    {
        if($this->isAdmin() || !isset($_SESSION['user']))
        {
            $this->render('error/errorpage', ['codeErreur' => 403, 'messageErreur' => 'Vous n\'avez pas les droits pour accéder à cette page']);
            return;
        }
        if ($_SESSION['user']) {
            $sauvegarde = new Sauvegarde();
            $grid_ids = $sauvegarde->getGridsByUserId($_SESSION['user']['id']); // Liste des IDs
            
            $grids = [];
            foreach ($grid_ids as $grid_id) {
                $grid = Grid::getById($grid_id["grille_id"]);
                if ($grid) {
                    $grids[] = $grid;
                }
            }
            

            // Rendre la vue avec la liste complète des grilles
            $this->render('grids/sauvegardes/liste', ['title' => 'Liste des grilles sauvegardées','grids' => $grids]);
        } else {
            $this->redirect('/');
        }
    }

    // mise en place des api pour resoudre la grille
    public function getGridJson($gridId)
    {
        $grid = Grid::getById($gridId);

        if (!$grid) {
            http_response_code(404);
            echo json_encode(['error' => 'Grille introuvable']);
            return;
        }

        echo json_encode($grid);
    }
    public function getDefinitionsJson($gridId)
    {
        $definitions = Definition::getByGridId($gridId);
    
        if (empty($definitions)) {
            http_response_code(404);
            echo json_encode(['error' => 'Aucune définition trouvée pour cette grille']);
            return;
        }
    
        echo json_encode($definitions);
    }
    public function getCellsJson($gridId)
    {
        // Récupérer les cellules de la grille
        $cells = Cell::getByGridId($gridId);
        foreach ($cells as &$cell) {
            if ($cell['value'] !== null) {
                $cell['value'] = '#'; // Remplace la valeur par '#'
            }
            
        }

        if (isset($_SESSION['user'])) {
        
            $userId = $_SESSION['user']['id'];
            $sauvegarde = new Sauvegarde();

            // Charger la sauvegarde pour l'utilisateur et la grille
            $etatGrille = $sauvegarde->chargerSauvegarde($userId, $gridId);

            

            if (empty($cells)) {
                http_response_code(404);
                echo json_encode(['error' => 'Aucune cellule trouvée pour cette grille']);
                return;
            }

            // Remplacement des # avec les valeurs de la sauvegarde
            if (isset($etatGrille) && $etatGrille !== null) {
                $etatGrille = json_decode($etatGrille);
                
                foreach ($cells as $index => &$cell) {
                    if ($cell['value'] !== null) {
                        $cell['value'] = '#'; // Remplace la valeur par '#'
                    }
                    // Vérifier que l'indice existe dans $etatGrille
                    if (isset($etatGrille[$index])) {
                        
                        $etatCell = $etatGrille[$index];
                    
                        // Remplacer la valeur si elle n'est pas nulle
                        if (isset($etatCell->value) && $etatCell->value !== null) {
                            $cell['value'] = $etatCell->value;
                        }
                    }
                }
            
            } 
        }

        echo json_encode($cells);
    }

    public function getAllGridsJson()
    {
        $grids = Grid::getAll();

        if (empty($grids)) {
            http_response_code(404);
            echo json_encode('Aucune grille disponible');
            return;
        }

        echo json_encode(['grids' => $grids]);
    }
    public function verificationCellsJSON($gridId)
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            // Récupérer et décoder les données JSON envoyées
            $cellsJSON = json_decode($_POST['cells'], true);
            // Vérifier si les données sont au bon format
            if (!is_array($cellsJSON)) {
                http_response_code(400); // Mauvaise requête
                echo json_encode("Le format des données n'est pas valide");
                return;
            }
            // Récupérer les cellules de la base de données
            $cells = Cell::getByGridId($gridId);
            // Vérifier si des cellules existent pour cette grille
            if (empty($cells)) {
                http_response_code(404); // Grille non trouvée
                echo json_encode(false); // `is_resolved` = false
                return;
            }
            // Préparer les cellules de la base de données sous un format [ligne, colonne] => valeur
            $cellsMap = [];
            foreach ($cells as $cell) {
                $key = $cell['ligne'] . ',' . $cell['colonne'];
                $cellsMap[$key] = $cell['value'];
            }
            // Vérification des valeurs
            $is_resolved = "La grille est résolu";
            foreach ($cellsJSON as $jsonCell) {
                if (
                    !isset($jsonCell['ligne'], $jsonCell['colonne']) || // Vérifier la structure de chaque cellule
                    !is_int($jsonCell['ligne']) ||
                    !is_int($jsonCell['colonne'])
                ) {
                    http_response_code(400); // Mauvaise requête
                    echo json_encode(isset($jsonCell['ligne']));
                    return;
                }
                // Générer la clé pour correspondre au format de `cellsMap`
                $key = $jsonCell['ligne'] . ',' . $jsonCell['colonne'];
                // Comparer la valeur de la cellule JSON à celle de la base de données
                if ($this->capitalize($cellsMap[$key]) !== $this->capitalize($jsonCell['value'])) {
                    $is_resolved = "La grille n'est pas encore résolu";
                    break;
                }
            }
            // Retourner le résultat
            echo json_encode($is_resolved);
        } else {
            http_response_code(405); // Méthode non autorisée
            echo json_encode("Mauvaise méthode, il faut utiliser post");
        }
    }
}
