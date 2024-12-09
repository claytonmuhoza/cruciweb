<?php
namespace App\Models;
use PDO;
class Utilisateur {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function inscrire($email, $password) {
        $sql = "INSERT INTO utilisateurs (email, password, role) VALUES (:email, :password, 'utilisateur')";
        $stmt = $this->db->prepare($sql);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $stmt->execute([':email' => $email, ':password' => $hashedPassword]);
    }

    public function connecter($email, $password) {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            return $utilisateur;
        }
        return false;
    }

    public function supprimer($id) {
        $sql = "DELETE FROM utilisateurs WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    public function existe($email) {
        $sql = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

}
