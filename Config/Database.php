<?php

return [
    'host' => 'localhost',
    'dbname' => 'mots_croises',
    'username' => 'root',
    'password' => 'abagabo',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];
