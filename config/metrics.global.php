<?php

return [
    'metrics' => [
        'namespace' => 'example',
        'request_duration_seconds' => [
            'labels' => [
                'path',
                'method'
            ]
        ],
        'some_counter' => [
            'help' => 'it increases',
            'labels' => [
                'type'
            ]
        ],
        'some_gauge' => [

            'help' => 'it sets',
            'labels' => [
                'type'
            ]
        ],
        'some_histogram' => [

            'help' => 'it observes',
            'labels' => [
                'type'
            ]
        ],
    ]
];
