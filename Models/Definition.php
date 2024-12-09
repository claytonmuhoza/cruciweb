<?php
namespace App\Models;

use App\Models\Database;
use PDO;
use PDOException;

class Definition
{
    /**
     * Ajoute une définition à la grille.
     *
     * @param int $gridId ID de la grille.
     * @param string $type Type de définition ('row' ou 'column').
     * @param int $index_num Numéro de la ligne ou colonne.
     * @param string $text Texte de la définition.
     * @return bool True si ajouté avec succès, False sinon.
     */
    public static function add(int $gridId, string $type, int $index_num, string $text): bool
    {
        try {
            $pdo = Database::getConnection();
            $sql = "INSERT INTO definitions (grid_id, type, index_num, text) VALUES (:grid_id, :type, :index_num, :text)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':grid_id' => $gridId,
                ':type' => $type,
                ':index_num' => $index_num,
                ':text' => $text,
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la définition : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère toutes les définitions associées à une grille.
     *
     * @param int $gridId ID de la grille.
     * @return array Liste des définitions.
     */
    public static function getByGridId(int $gridId): array
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM definitions WHERE grid_id = :grid_id ORDER BY type, index_num";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':grid_id' => $gridId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des définitions : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprime toutes les définitions associées à une grille.
     *
     * @param int $gridId ID de la grille.
     * @return bool True si supprimées avec succès, False sinon.
     */
    public static function deleteByGridId(int $gridId): bool
    {
        try {
            $pdo = Database::getConnection();
            $sql = "DELETE FROM definitions WHERE grid_id = :grid_id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([':grid_id' => $gridId]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression des définitions : " . $e->getMessage());
            return false;
        }
    }
}
