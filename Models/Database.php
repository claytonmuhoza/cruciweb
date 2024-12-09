<?php
namespace App\Models;
use PDO;
use PDOException;
class Database {
    private static $pdo;

    public static function getConnection() {
        $config = require __DIR__ . '/../Config/Database.php';
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";
                $username = $config['username'];
                $password = $config['password'];
                self::$pdo = new PDO($dsn, $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
