#!/bin/bash

# Chemin du fichier de configuration
DB_CONFIG_FILE="/var/www/html/Config/Database.php"

# Fonction pour demander une entrée utilisateur avec une valeur par défaut
ask() {
    local prompt="$1"
    local default="$2"
    local input
    read -p "$prompt [$default]: " input
    echo "${input:-$default}"
}

# Vérification de l'existence du répertoire de configuration
if [ ! -d "$(dirname "$DB_CONFIG_FILE")" ]; then
    echo "Le répertoire $(dirname "$DB_CONFIG_FILE") n'existe pas. Création en cours..."
    mkdir -p "$(dirname "$DB_CONFIG_FILE")" || { echo "Erreur lors de la création du répertoire."; exit 1; }
fi

# Demander les informations de connexion
host=$(ask "Entrez le nom de l'hôte" "localhost")
dbname=$(ask "Entrez le nom de la base de données" "projet")
username=$(ask "Entrez le nom d'utilisateur" "projet")
password=$(ask "Entrez le mot de passe" "tejorp")

# Générer le contenu du fichier de configuration
cat > "$DB_CONFIG_FILE" <<EOL
<?php

return [
    'host' => '$host',
    'dbname' => '$dbname',
    'username' => '$username',
    'password' => '$password',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
EOL

# Vérification de la réussite de l'écriture
if [ $? -eq 0 ]; then
    echo "Fichier de configuration mis à jour avec succès : $DB_CONFIG_FILE"
else
    echo "Erreur lors de la mise à jour du fichier de configuration." >&2
    exit 1
fi
