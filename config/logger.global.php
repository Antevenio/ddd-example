<?php

return [
    'logger' => [
        'name' => 'example-backend',
        'level' => APPLICATION_ENV == 'production' ? \Monolog\Logger::ERROR : \Monolog\Logger::DEBUG,
        'path' => __DIR__ . '/../logs/app.log',
    ],
];
