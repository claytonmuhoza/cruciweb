<?php

namespace App\Models;
use PDO;
use PDOException;
class Definition
{
    /**
     * Récupère toutes les définitions associées à une grille.
     *
     * @param int $gridId ID de la grille.
     * @return array Liste des définitions.
     */
    public static function getByGridId(int $gridId)
    {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM definitions WHERE grid_id = :grid_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':grid_id' => $gridId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime toutes les définitions associées à une grille.
     *
     * @param int $gridId ID de la grille.
     * @return void
     */
    public static function deleteByGridId(int $gridId)
    {
        $pdo = Database::getConnection();
        $sql = "DELETE FROM definitions WHERE grid_id = :grid_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':grid_id' => $gridId]);
    }
}
