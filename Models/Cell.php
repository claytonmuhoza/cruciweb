<?php
namespace App\Models;

use App\Models\Database;
use PDO;

class Cell {
    private int $id;
    private int $grid_id;
    private int $ligne;
    private int $colonne;
    private ?string $value; // `value` peut être NULL si la cellule est vide

    // Constructeur
    public function __construct(int $grid_id, int $ligne, int $colonne, ?string $value = null) {
        $this->grid_id = $grid_id;
        $this->ligne = $ligne;
        $this->colonne = $colonne;
        $this->value = $value;
    }

    // Méthodes CRUD

    // Création d'une cellule
    public function save(): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO cells (grid_id, ligne, colonne, value) VALUES (:grid_id, :ligne, :colonne, :value)");
        return $stmt->execute([
            ':grid_id' => $this->grid_id,
            ':ligne' => $this->ligne,
            ':colonne' => $this->colonne,
            ':value' => $this->value,
        ]);
    }

    // Lecture des cellules par grille
    public static function getByGridId(int $grid_id): array {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM cells WHERE grid_id = :grid_id ORDER BY ligne, colonne");
        $stmt->execute([':grid_id' => $grid_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mise à jour d'une cellule
    public static function update(int $id, ?string $value): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE cells SET value = :value WHERE id = :id");
        return $stmt->execute([
            ':value' => $value,
            ':id' => $id,
        ]);
    }

    // Suppression des cellules d'une grille
    public static function deleteByGridId(int $grid_id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM cells WHERE grid_id = :grid_id");
        return $stmt->execute([':grid_id' => $grid_id]);
    }
}
