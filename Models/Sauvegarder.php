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
        $sql = "REPLACE INTO sauvegardes (utilisateur_id, grille_id, etat_grille) VALUES (:utilisateur_id, :grille_id, :etat_grille)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':utilisateur_id' => $utilisateur_id,
            ':grille_id' => $grille_id,
            ':etat_grille' => json_encode($etatGrille)
        ]);
    }

    public function chargerSauvegarde($utilisateur_id, $grille_id) {
        $sql = "SELECT etat_grille FROM sauvegardes WHERE utilisateur_id = :utilisateur_id AND grille_id = :grille_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateur_id, ':grille_id' => $grille_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? json_decode($result['etat_grille'], true) : null;
    }

    public function supprimerSauvegarde($utilisateur_id, $grille_id) {
        $sql = "DELETE FROM sauvegardes WHERE utilisateur_id = :utilisateur_id AND grille_id = :grille_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':utilisateur_id' => $utilisateur_id, ':grille_id' => $grille_id]);
    }
}
