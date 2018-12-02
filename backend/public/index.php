<?php

use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

if (file_exists('../config/.env')) {
    $env = new Dotenv();
    $env->load('../config/.env');
}

$settings = require 'src/Config/settings.php';

$app = new \Slim\App($settings);

require 'src/Config/dependencies.php';

require 'src/Config/routes.php';

$app->run();
