<?php

require_once __DIR__.'/../../vendor/james-heinrich/getid3/getid3/getid3.php';

use FileHosting\Controller\CommentsController;
use FileHosting\Controller\FileController;
use FileHosting\Controller\SearchController;
use FileHosting\Controller\SiteController;
use FileHosting\Handler\ErrorHandler;
use FileHosting\Handler\NotFoundHandler;
use FileHosting\Helper\CommentsHelper;
use FileHosting\Helper\File\GetID3Helper;
use FileHosting\Helper\File\PathHelper;
use FileHosting\Helper\File\PreviewHelper;
use FileHosting\Helper\FileHelper;
use FileHosting\Helper\SearchHelper;
use FileHosting\Repository\CommentsRepository;
use FileHosting\Repository\FileRepository;
use FileHosting\Repository\SearchRepository;
use FileHosting\Validator\CommentValidator;
use FileHosting\Validator\FileValidator;
use Monolog\Logger;
use Slim\Container;

$container = $app->getContainer();

// errorHandlers
$container['errorHandler'] = function (Container $c) : ErrorHandler {
    return new ErrorHandler($c['logger']);
};

$container['notFoundHandler'] = function (Container $c) : NotFoundHandler {
    return new NotFoundHandler($c['logger']);
};

// pdo
$container['PDO'] = function (Container $c) : PDO {
    $config = $c['settings']['pdo'];

    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8',
        $config['db_host'],
        $config['db_name']
    );
    $pdo = new PDO(
        $dsn,
        $config['db_user'],
        $config['db_pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
};

// sphinx
$container['sphinx'] = function (Container $c) : PDO {
    $config = $c['settings']['sphinx'];

    $dsn = sprintf(
        'mysql:host=%s;port=%s;charset=utf8',
        $config['db_host'],
        $config['db_port']
    );
    $sphinx = new PDO($dsn);
    $sphinx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $sphinx;
};

// getID3
$container['getid3Helper'] = function (Container $c) : getID3Helper {
    return new getid3Helper(new getID3(), $c['settings']['file_info']);
};

// monolog
$container['logger'] = function (Container $c): Logger {
    $settings = $c['settings']['logger'];
    $logger = new Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

// validators
$container['FileValidator'] = function (Container $c): FileValidator {
    return new FileValidator($c['settings']['file']);
};

$container['CommentValidator'] = function (Container $c): CommentValidator {
    return new CommentValidator($c['settings']['comment']);
};

// repositories
$container['FileRepository'] = function (Container $c): FileRepository {
    return new FileRepository($c['PDO']);
};

$container['CommentsRepository'] = function (Container $c): CommentsRepository {
    return new CommentsRepository($c['PDO']);
};

$container['SearchRepository'] = function (Container $c): SearchRepository {
    return new SearchRepository($c['sphinx']);
};

// helpers
$container['FileHelper'] = function (Container $c): FileHelper {
    return new FileHelper($c['PreviewHelper'], $c['FileRepository'], $c['PathHelper']);
};

$container['SearchHelper'] = function (Container $c): SearchHelper {
    return new SearchHelper($c['SearchRepository'], $c['FileRepository']);
};

$container['PathHelper'] = function (Container $c): PathHelper {
    return new PathHelper($c['settings']['file'], $c['settings']['preview']);
};

$container['PreviewHelper'] = function (Container $c): PreviewHelper {
    return new PreviewHelper($c['settings']['preview']);
};

$container['CommentsHelper'] = function (Container $c): CommentsHelper {
    return new CommentsHelper($c['CommentsRepository']);
};

// controllers
$container['FileController'] = function (Container $c): FileController {
    return new FileController(
        $c['FileValidator'],
        $c['FileHelper'],
        $c['FileRepository'],
        $c['SearchRepository'],
        $c['getid3Helper']
    );
};

$container['SiteController'] = function (Container $c): SiteController {
    return new SiteController($c['FileHelper'], $c['FileRepository'], $c['settings']['file']);
};

$container['CommentsController'] = function (Container $c): CommentsController {
    return new CommentsController(
        $c['CommentValidator'],
        $c['CommentsHelper'],
        $c['CommentsRepository']
    );
};

$container['SearchController'] = function (Container $c): SearchController {
    return new SearchController($c['SearchHelper']);
};
