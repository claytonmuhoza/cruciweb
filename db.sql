CREATE DATABASE mots_croises;
USE mots_croises;
# Création des tables

CREATE TABLE utilisateurs (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,role ENUM('utilisateur', 'admin') DEFAULT 'utilisateur',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE grids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    difficulty ENUM('easy', 'medium', 'hard') NOT NULL,
    user_id INT NOT NULL, -- Référence à un utilisateur
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE definitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grid_id INT NOT NULL, -- Référence à la table grids
    type ENUM('row', 'column') NOT NULL, -- Type de définition (ligne ou colonne)
    index_num INT NOT NULL, -- Numéro de la ligne ou colonne
    text TEXT NOT NULL, -- Texte de la définition
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
);

CREATE TABLE cells (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grid_id INT NOT NULL, -- Référence à la table grids
    ligne INT NOT NULL, -- Numéro de la ligne 
    colonne INT NOT NULL, -- Numéro de la colonne 
    value CHAR(1), -- Contenu de la cellule 
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
);
