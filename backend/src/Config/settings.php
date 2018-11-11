<?php

return [
    'settings' => [
        'path'                   => '/api',
        'displayErrorDetails'    => true,
        'addContentLengthHeader' => true,

        'file' => [
            'path'         => __DIR__.'/../../storage',
            'max_size'     => '10485760',
        ],

        'preview' => [
            'path'    => __DIR__.'/../../storage/preview',
            'default' => '404.png',
            'width'   => '0',
            'height'  => '500',
        ],

        'comment' => [
            'message_length' => '200',
            'name_length'    => '30',

        ],

        'pdo' => [
            'db_host' => 'localhost',
            'db_name' => 'filehosting',
            'db_user' => 'test',
            'db_pass' => 'test',
        ],

        'sphinx' => [
            'db_host' => '127.0.0.1',
            'db_port' => '9306',
        ],

        'logger' => [
            'name'  => 'slim-app',
            'path'  => __DIR__.'/../../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
