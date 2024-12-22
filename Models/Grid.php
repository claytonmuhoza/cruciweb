<?php
namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class Grid
{
    /**
     * Crée une nouvelle grille et retourne son ID.
     *
     * @param array $data Données de la grille (name, difficulty, user_id).
     * @return int|null ID de la grille créée ou null en cas d'échec.
     */
    public static function save(array $data): ?int
    {
        try {
            $pdo = Database::getConnection();
            $sql = "INSERT INTO grids (name, difficulty, user_id) VALUES (:name, :difficulty, :user_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':difficulty' => $data['difficulty'],
                ':user_id' => $data['user_id'],
            ]);
            return (int) $pdo->lastInsertId();
        } catch (PDOException $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de l'enregistrement de la grille : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Ajoute des définitions à la grille.
     *
     * @param int $gridId ID de la grille.
     * @param array $definitions Définitions pour les lignes et colonnes.
     * @return bool True si toutes les définitions sont ajoutées avec succès, False sinon.
     */
    public static function addDefinitions(int $gridId, array $definitions): bool
    {
        try {
            $pdo = Database::getConnection();
            $sql = "INSERT INTO definitions (grid_id, type, index_num, text) VALUES (:grid_id, :type, :index_num, :text)";
            $stmt = $pdo->prepare($sql);

            // Définir les types valides
            $validTypes = ['row', 'column'];

            // Ajouter les définitions des lignes
            foreach ($definitions['rows'] as $index => $text) {
                if (in_array('row', $validTypes)) {
                    $stmt->execute([
                        ':grid_id' => $gridId,
                        ':type' => 'row',
                        ':index_num' => $index + 1, // Indice à partir de 1
                        ':text' => $text,
                    ]);
                }
            }

            // Ajouter les définitions des colonnes
            foreach ($definitions['columns'] as $index => $text) {
                if (in_array('column', $validTypes)) {
                    $stmt->execute([
                        ':grid_id' => $gridId,
                        ':type' => 'column',
                        ':index_num' => $index + 1, // Indice à partir de 1
                        ':text' => $text,
                    ]);
                }
            }

            return true;
        } catch (PDOException $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de l'ajout des définitions : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ajoute les cellules de la grille.
     *
     * @param int $gridId ID de la grille.
     * @param array $cells Tableau des cellules de la grille.
     * @return bool True si toutes les cellules sont ajoutées avec succès, False sinon.
     */
    public static function addCells(int $gridId, array $cells): bool
    {
        try {
            $pdo = Database::getConnection();
            $sql = "INSERT INTO cells (grid_id, ligne, colonne, value) VALUES (:grid_id, :ligne, :colonne, :value)";
            $stmt = $pdo->prepare($sql);

            foreach ($cells as $ligne => $row) {
                foreach ($row as $colonne => $value) {
                    $stmt->execute([
                        ':grid_id' => $gridId,
                        ':ligne' => $ligne + 1,     // Indice à partir de 1
                        ':colonne' => $colonne + 1, // Indice à partir de 1
                        ':value' => $value ?: null, // Valeur NULL pour les cases vides
                    ]);
                }
            }

            return true;
        } catch (PDOException $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de l'ajout des cellules : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère une grille par son ID.
     *
     * @param int $id ID de la grille.
     * @return array|null Les données de la grille ou null si introuvable.
     */
    public static function getById(int $id): ?array
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM grids WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $grid = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($grid) {
                // Récupérer le nombre de lignes et de colonnes
                $sql = "SELECT MAX(ligne) as max_ligne, MAX(colonne) as max_colonne FROM cells WHERE grid_id = :grid_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':grid_id' => $id]);
                $dimensions = $stmt->fetch(PDO::FETCH_ASSOC);

                $grid['num_rows'] = $dimensions['max_ligne'] ?? 0;
                $grid['num_columns'] = $dimensions['max_colonne'] ?? 0;
            }

            return $grid ?: null;
        } catch (PDOException $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de la récupération de la grille : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Supprime une grille par son ID.
     *
     * @param int $id ID de la grille à supprimer.
     * @return bool True si supprimée avec succès, False sinon.
     */
    public static function delete(int $id): bool
    {
        try {
            $pdo = Database::getConnection();
            $sql = "DELETE FROM grids WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de la suppression de la grille : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère toutes les grilles.
     *
     * @return array Liste de toutes les grilles.
     */
    public static function getAll(): array
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM grids ORDER BY created_at DESC";
            $stmt = $pdo->query($sql);
            $grids = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($grids as &$grid) {
                $sql = "SELECT MAX(ligne) as max_ligne, MAX(colonne) as max_colonne FROM cells WHERE grid_id = :grid_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':grid_id' => $grid['id']]);
                $dimensions = $stmt->fetch(PDO::FETCH_ASSOC);

                $grid['num_rows'] = $dimensions['max_ligne'] ?? 0;
                $grid['num_columns'] = $dimensions['max_colonne'] ?? 0;
            }

            return $grids;
        } catch (PDOException $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de la récupération de toutes les grilles : " . $e->getMessage());
            return [];
        }
    }
}
