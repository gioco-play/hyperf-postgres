<?php

return [
    'default' => [
        'username' => env('POSTGRES_USERNAME', ''),
        'password' => env('POSTGRES_PASSWORD', ''),
        'host' => env('POSTGRES_HOST', '127.0.0.1'),
        'port' => env('POSTGRES_PORT', 5432),
        'database' => env('POSTGRES_DB', 'test'),
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 100,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float)env('POSTGRES_MAX_IDLE_TIME', 60),
        ],
    ],
];