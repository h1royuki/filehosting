<?php

$container = $app->getContainer();
$path = $container['settings']['path'];

$app->post($path.'/upload', \FileController::class.':upload');
$app->get($path.'/download/{id}[/{stream}]', \FileController::class.':download');
$app->get($path.'/preview/{id}', \FileController::class.':preview');
$app->get($path.'/search/{query}', \SearchController::class.':search');
$app->get($path.'/last', \SiteController::class.':last');
$app->get($path.'/file/{id}', \SiteController::class.':view');
$app->get($path.'/comments/{id}', \CommentsController::class.':view');
$app->post($path.'/comments', \CommentsController::class.':add');
