<?php

return [
    'rabbit' => [
        'host' => 'example_rabbitmq',
        'port' => 5672,
        'user' => 'guest',
        'password' => 'guest',
        'vhost' => '/',
        'exchange' => 'example_exchange',
        'amqp_debug' => true,
        'insist' => false,
        'login_method' => "AMQPLAIN",
        'login_response' => null,
        'locale' => "es_ES",
        'connection_timeout' => 30,
        'read_write_timeout' => 30,
        'context' => null,
        'keepalive' => true,
        'heartbeat' => 10,
    ]
];
