<?php

return [
    'db' => [
       'example' => [
            'dsn' => 'mysql:dbname=example;host=example_mysql',
            'driver_options' => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC
            ],
            'username' => 'myuser',
            'password' => 'secret',
        ]
    ]
];
