<?php
namespace App\Models;
use PDO;
use PDOException;
class Database {
    private static $pdo;

    public static function getConnection() {
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host=localhost;dbname=mots_croises;charset=utf8";
                $username = "root";
                $password = "abagabo";
                self::$pdo = new PDO($dsn, $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
