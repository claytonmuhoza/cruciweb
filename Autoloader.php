<?php

spl_autoload_register(function ($className) {
    // Définir le chemin de base (racine du projet)
    $baseDir = __DIR__ . '/';

    // Supprimer le préfixe "APP\" si présent au début du namespace
    if (strpos($className, 'App\\') === 0) {
        $className = substr($className, 4); // Supprimer "APP\"
    }

    // Transformer le namespace en chemin de fichier
    $classFile = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    // Vérifier si le fichier existe
    if (file_exists($classFile)) {
        require_once $classFile;
    } else {
        // Afficher une erreur explicite si le fichier n'est pas trouvé
        error_log("Autoloader : Impossible de charger la classe '$className'. Fichier attendu : $classFile");
        die("Erreur critique : La classe '$className' n'a pas pu être chargée. Assurez-vous que le fichier existe : $classFile");
    }
});
