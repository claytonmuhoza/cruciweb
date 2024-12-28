<?php

return [
    'host' => 'localhost',
    'dbname' => 'projet',
    'username' => 'projet',
    'password' => 'tejorp',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
