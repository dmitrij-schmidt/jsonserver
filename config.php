<?php

return [
    'database' => [
        'driver' => 'json',
        'name' => 'Kwiziq',
        'connection' => './db/',
        'database' => 'kwiziq'
    ],
    'routes' => [
        'web' => null,
        'api' => 'app/routes/api.php',
    ]
];