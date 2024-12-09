<?php
namespace App\Models;
use PDO;
use PDOException;
class Grille {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function creerGrille($nom, $dimensions, $niveau, $cases_noires, $definitions, $solution, $utilisateur_id) {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO grilles (nom, dimensions, niveau, utilisateur_id) VALUES (:nom, :dimensions, :niveau, :utilisateur_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':dimensions' => $dimensions,
                ':niveau' => $niveau,
                ':utilisateur_id' => $utilisateur_id
            ]);

            $grille_id = $this->db->lastInsertId();

            foreach ($cases_noires as $case) {
                $sqlCase = "INSERT INTO cases (grille_id, ligne, colonne, est_noire) VALUES (:grille_id, :ligne, :colonne, 1)";
                $stmtCase = $this->db->prepare($sqlCase);
                $stmtCase->execute([
                    ':grille_id' => $grille_id,
                    ':ligne' => $case['ligne'],
                    ':colonne' => $case['colonne']
                ]);
            }

            foreach ($definitions as $definition) {
                $sqlDef = "INSERT INTO definitions (grille_id, texte, type) VALUES (:grille_id, :texte, :type)";
                $stmtDef = $this->db->prepare($sqlDef);
                $stmtDef->execute([
                    ':grille_id' => $grille_id,
                    ':texte' => $definition['texte'],
                    ':type' => $definition['type']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function listerGrilles() {
        $sql = "SELECT * FROM grilles ORDER BY date_creation DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerGrille($id) {
        $sql = "DELETE FROM grilles WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
