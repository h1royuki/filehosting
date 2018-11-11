<?php

require __DIR__.'/../vendor/autoload.php';

$settings = require __DIR__.'/../src/Config/settings.php';
$app = new \Slim\App($settings);

require __DIR__.'/../src/Config/dependencies.php';

require __DIR__.'/../src/Config/routes.php';

$app->run();
