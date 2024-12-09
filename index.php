<?php

// Activer les erreurs en développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// require_once __DIR__ . '/Autoloader.php';
// Charger les configurations
require_once __DIR__ . '/Autoloader.php';
require_once __DIR__ . '/Config/routes.php';