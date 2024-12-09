<?php
namespace App\Models;

use App\Models\Database;
use PDO;

class Grid
{
    /**
     * Crée une nouvelle grille.
     *
     * @param array $data Données de la grille (name, difficulty, user_id).
     * @return int ID de la grille créée.
     */
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO grids (name, difficulty, user_id) VALUES (:name, :difficulty, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':difficulty' => $data['difficulty'],
            ':user_id' => $data['user_id'],
        ]);
        return $pdo->lastInsertId();
    }

    /**
     * Ajoute des définitions à la grille.
     *
     * @param int $gridId ID de la grille.
     * @param array $definitions Définitions pour les lignes et colonnes.
     * @return void
     */
    public static function addDefinitions(int $gridId, array $definitions): void
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO definitions (grid_id, type, index_num, text) VALUES (:grid_id, :type, :index_num, :text)";
        $stmt = $pdo->prepare($sql);

        foreach ($definitions['rows'] as $index => $text) {
            $stmt->execute([
                ':grid_id' => $gridId,
                ':type' => 'row',
                ':index_num' => $index,
                ':text' => $text,
            ]);
        }

        foreach ($definitions['columns'] as $index => $text) {
            $stmt->execute([
                ':grid_id' => $gridId,
                ':type' => 'column',
                ':index_num' => $index,
                ':text' => $text,
            ]);
        }
    }

    /**
     * Ajoute les cellules de la grille.
     *
     * @param int $gridId ID de la grille.
     * @param array $cells Tableau des cellules de la grille.
     * @return void
     */
    public static function addCells(int $gridId, array $cells): void
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO cells (grid_id, ligne, colonne, value) VALUES (:grid_id, :ligne, :colonne, :value)";
        $stmt = $pdo->prepare($sql);

        foreach ($cells as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $stmt->execute([
                    ':grid_id' => $gridId,
                    ':ligne' => $rowIndex,
                    ':colonne' => $colIndex,
                    ':value' => $value ?: null, // Valeur vide pour les cases vides.
                ]);
            }
        }
    }

    /**
     * Récupère une grille par son ID.
     *
     * @param int $id ID de la grille.
     * @return array|null Les données de la grille ou null si introuvable.
     */
    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM grids WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Sauvegarde une grille avec ses données.
     *
     * @param string $name Nom de la grille.
     * @param string $difficulty Niveau de difficulté.
     * @param int $user_id ID de l'utilisateur.
     * @return int|null ID de la grille créée ou null en cas d'échec.
     */
    public static function save(string $name, string $difficulty, int $user_id): ?int
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO grids (name, difficulty, user_id) VALUES (:name, :difficulty, :user_id)";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([
            ':name' => $name,
            ':difficulty' => $difficulty,
            ':user_id' => $user_id,
        ]);

        return $success ? $pdo->lastInsertId() : null;
    }

    /**
     * Récupère une grille par ID.
     *
     * @param int $id ID de la grille.
     * @return array|null Grille récupérée ou null si introuvable.
     */
    public static function getById(int $id): ?array
    {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM grids WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Supprime une grille.
     *
     * @param int $id ID de la grille à supprimer.
     * @return bool True si supprimée avec succès, False sinon.
     */
    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $sql = "DELETE FROM grids WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
