<?php
namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class Cell
{
    /**
     * Ajoute une cellule à la grille.
     *
     * @param int $gridId ID de la grille.
     * @param int $ligne Numéro de la ligne.
     * @param int $colonne Numéro de la colonne.
     * @param string|null $value Valeur de la cellule (lettre ou NULL).
     * @return bool True si ajouté avec succès, False sinon.
     */
    public static function add(int $gridId, int $ligne, int $colonne, ?string $value): bool
    {
        try {
            $pdo = Database::getConnection();
            $sql = "INSERT INTO cells (grid_id, ligne, colonne, value) VALUES (:grid_id, :ligne, :colonne, :value)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':grid_id' => $gridId,
                ':ligne' => $ligne,
                ':colonne' => $colonne,
                ':value' => $value,
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la cellule : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère toutes les cellules associées à une grille.
     *
     * @param int $gridId ID de la grille.
     * @return array Liste des cellules.
     */
    public static function getByGridId(int $gridId): array
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM cells WHERE grid_id = :grid_id ORDER BY ligne, colonne";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':grid_id' => $gridId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des cellules : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprime toutes les cellules associées à une grille.
     *
     * @param int $gridId ID de la grille.
     * @return bool True si supprimées avec succès, False sinon.
     */
    public static function deleteByGridId(int $gridId): bool
    {
        try {
            $pdo = Database::getConnection();
            $sql = "DELETE FROM cells WHERE grid_id = :grid_id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':grid_id' => $gridId]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression des cellules : " . $e->getMessage());
            return false;
        }
    }
}
