<?php

return [
    'settings' => [
        'displayErrorDetails'    => (bool) getenv('DEBUG'),
        'addContentLengthHeader' => true,

        'file' => [
            'path'         => getenv('FILE_PATH'),
            'max_size'     => (int) getenv('FILE_MAXSIZE'),
            'last_limit'   => '12',
        ],

        'file_info' => [
            'archive_items' => 10,
        ],

        'preview' => [
            'path'    => getenv('PREVIEW_PATH'),
            'default' => '404.png',
            'width'   => '0',
            'height'  => '500',
        ],

        'comment' => [
            'message_length' => '200',
            'name_length'    => '30',
        ],

        'pdo' => [
            'db_host' => getenv('DB_HOST'),
            'db_name' => getenv('DB_NAME'),
            'db_user' => getenv('DB_USER'),
            'db_pass' => getenv('DB_PASSWORD'),
            'db_port' => getenv('DB_PORT')
        ],

        'sphinx' => [
            'db_host' => getenv('SPHINX_HOST'),
            'db_port' => getenv('SPHINX_PORT'),
        ],

        'logger' => [
            'name'  => 'slim-app',
            'path'  => getenv('MONOLOG_FILE'),
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
