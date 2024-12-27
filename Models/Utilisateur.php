<?php
namespace App\Models;
use PDO;
class Utilisateur {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function inscrire($username, $password) {
        $sql = "INSERT INTO utilisateurs (username, password, role) VALUES (:username, :password, 'utilisateur')";
        $stmt = $this->db->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $stmt->execute([':username' => $username, ':password' => $hashedPassword]);
    }

    public function connecter($username, $password) {
        $sql = "SELECT * FROM utilisateurs WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            return $utilisateur;
        }
        return false;
    }
    public function afficherTous() {
        $sql = "SELECT * FROM utilisateurs";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function supprimer($id) {
        $sql = "DELETE FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function existe($username) {
        $sql = "SELECT COUNT(*) FROM utilisateurs WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

}
