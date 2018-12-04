<?php

use FileHosting\Middleware\FileNotFoundMiddleware;
use FileHosting\Middleware\ValidationMiddleware;


$app->add( new FileNotFoundMiddleware());
$app->add( new ValidationMiddleware());

