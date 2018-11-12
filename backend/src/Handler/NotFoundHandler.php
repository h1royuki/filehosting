<?php

namespace FileHosting\Handler;

use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;

class NotFoundHandler
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $this->logger->info('404', ['url' => $request->getUri()]);

        return $response->withStatus(404)->withJson(['error' => 'Page not found']);
    }
}
