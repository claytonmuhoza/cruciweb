CREATE DATABASE mots_croises;
USE mots_croises;
# Création des tables
## 1. Table `users`
CREATE TABLE utilisateurs (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,role ENUM('utilisateur', 'admin') DEFAULT 'utilisateur',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
## 2. Table `grids`
CREATE TABLE grilles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    difficulty ENUM('beginner', 'intermediate', 'expert') NOT NULL,
    grid_definition TEXT NOT NULL,  -- Définitions des mots croisés
    solution TEXT NOT NULL,         -- Solution de la grille
    created_by INT NOT NULL,       -- Utilisateur qui a créé la grille
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
## 3. Table `grid_cells`
CREATE TABLE grid_cells (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grid_id INT NOT NULL,
    row INT NOT NULL,
    col INT NOT NULL,
    is_black BOOLEAN NOT NULL DEFAULT 0,  -- Si c'est une case noire
    letter CHAR(1),                       -- Lettre dans la case (si pas noire)
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
);
## 4. Table `definitions`
CREATE TABLE definitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grid_id INT NOT NULL,
    direction ENUM('horizontal', 'vertical') NOT NULL,  -- Direction de la définition
    position INT NOT NULL,                               -- Position (ligne ou colonne)
    description TEXT NOT NULL,                           -- Description de la définition
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
);
## 5. Table `grid_solutions`
CREATE TABLE grid_solutions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    grid_id INT NOT NULL,
    solution TEXT NOT NULL,          -- Solution soumise par l'utilisateur
    solved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date de la résolution
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
);
CREATE TABLE saved_grids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,           -- Référence à l'utilisateur ayant sauvegardé la grille
    grid_id INT NOT NULL,           -- Référence à la grille sauvegardée
    saved_solution TEXT,            -- Solution partielle sauvegardée
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Date de la sauvegarde
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (grid_id) REFERENCES grids(id) ON DELETE CASCADE
);
