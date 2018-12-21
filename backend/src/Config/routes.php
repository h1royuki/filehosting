<?php

$app->post('/upload', \FileController::class . ':upload');
$app->get('/download/{id}[/{stream}]', \FileController::class . ':download');
$app->get('/preview/{id}', \FileController::class . ':preview');
$app->get('/search', \SearchController::class . ':search');
$app->get('/last', \SiteController::class . ':last');
$app->get('/file/{id}', \SiteController::class . ':view');
$app->get('/comments/{id}', \CommentsController::class . ':view');
$app->post('/comments', \CommentsController::class . ':add');
