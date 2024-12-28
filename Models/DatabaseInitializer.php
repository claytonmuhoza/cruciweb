<?php

namespace App\Models;

use PDO;
use PDOException;

class DatabaseInitializer
{
    public static function initialize()
    {
        $config = require __DIR__ . '/../Config/Database.php';
        $pdo = Database::getConnection();

        try {
            // Vérifie si la base de données existe
            $pdo->exec("CREATE DATABASE IF NOT EXISTS " . $config['dbname']);
            $pdo->exec("USE " . $config['dbname']);

            // Crée les tables si elles n'existent pas déjà
            $createTablesQueries = [
                // Table utilisateurs
                "CREATE TABLE IF NOT EXISTS utilisateurs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    role ENUM('utilisateur', 'admin') DEFAULT 'utilisateur',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )",

                // Table grids
                "CREATE TABLE IF NOT EXISTS grids (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )",

                // Table definitions
                "CREATE TABLE IF NOT EXISTS definitions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    grid_id INT NOT NULL,
                    type ENUM('row', 'column') NOT NULL,
                    index_num INT NOT NULL,
                    text TEXT NOT NULL,
                    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
                )",

                // Table cells
                "CREATE TABLE IF NOT EXISTS cells (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    grid_id INT NOT NULL,
                    ligne INT NOT NULL,
                    colonne INT NOT NULL,
                    value CHAR(1),
                    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
                )",

                // Table sauvegardes
                "CREATE TABLE IF NOT EXISTS sauvegardes (
                    utilisateur_id INT NOT NULL,
                    grille_id INT NOT NULL,
                    etat_grille TEXT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (utilisateur_id, grille_id)
                )"
            ];

            // Exécution des requêtes
            foreach ($createTablesQueries as $query) {
                $pdo->exec($query);
            }

            // Insère un utilisateur admin par défaut
            $pdo->exec("
                INSERT IGNORE INTO utilisateurs (username, password, role) 
                VALUES ('admin', '\$2y\$10\$vkPVM4pRFfKPMofH5uZGPOu28YRONDEkMospqlyzZY8DnFhYqSOU6', 'admin')
            ");


        } catch (PDOException $e) {
            die("Erreur lors de l'initialisation de la base de données : " . $e->getMessage());
        }
    }
}
