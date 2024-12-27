<?php
namespace App\Models;
use PDO;
use PDOException;
class Sauvegarde {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function sauvegarderGrille($utilisateur_id, $grille_id, $etatGrille) {
        // Vérifie si une sauvegarde existe déjà pour la grille
        $checkSql = "SELECT COUNT(*) FROM sauvegardes WHERE utilisateur_id = :utilisateur_id AND grille_id = :grille_id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->execute([
            ':utilisateur_id' => $utilisateur_id,
            ':grille_id' => $grille_id
        ]);
    
        $exists = $checkStmt->fetchColumn() > 0;
    
        if ($exists) {
            // Met à jour l'état de la grille existante
            $updateSql = "UPDATE sauvegardes SET etat_grille = :etat_grille WHERE utilisateur_id = :utilisateur_id AND grille_id = :grille_id";
            $updateStmt = $this->db->prepare($updateSql);
            return $updateStmt->execute([
                ':etat_grille' => json_encode($etatGrille),
                ':utilisateur_id' => $utilisateur_id,
                ':grille_id' => $grille_id
            ]);
        } else {
            // Insère une nouvelle sauvegarde
            $insertSql = "INSERT INTO sauvegardes (utilisateur_id, grille_id, etat_grille) VALUES (:utilisateur_id, :grille_id, :etat_grille)";
            $insertStmt = $this->db->prepare($insertSql);
            return $insertStmt->execute([
                ':utilisateur_id' => $utilisateur_id,
                ':grille_id' => $grille_id,
                ':etat_grille' => json_encode($etatGrille)
            ]);
        }
    }
    

    public function chargerSauvegarde($utilisateur_id, $grille_id) {
        $sql = "SELECT etat_grille FROM sauvegardes WHERE utilisateur_id = :utilisateur_id AND grille_id = :grille_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateur_id, ':grille_id' => $grille_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? json_decode($result['etat_grille'], true) : null;
    }
    public function getGridsByUserId($utilisateur_id) {
        $sql = "SELECT grille_id  FROM sauvegardes WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateur_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function deleteByUser($utilisateur_id)
    {
        $sql = "DELETE FROM sauvegardes WHERE utilisateur_id = :utilisateur_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':utilisateur_id' => $utilisateur_id]);
    }
    public function deleteByGrid($grille_id)
    {
        $sql = "DELETE FROM sauvegardes WHERE grille_id = :grille_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':grille_id' => $grille_id]);
    }
}
