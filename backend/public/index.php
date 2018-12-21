<?php

use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

if (file_exists('.env')) {
    $env = new Dotenv();
    $env->load('.env');
}

$settings = require 'src/Config/settings.php';

$app = new \Slim\App($settings);

require 'src/Config/dependencies.php';

require 'src/Config/middleware.php';

require 'src/Config/routes.php';

$app->run();
