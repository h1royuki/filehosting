<?php

namespace FileHosting\Handler;

use FileHosting\Exception\FileNotFoundException;
use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorHandler
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, \Exception $exception): Response
    {
        $this->logger->critical($exception->getMessage(), $exception->getTrace());

        return $response
            ->withStatus(500)
            ->withJson(['error' => $exception->getMessage()]);
    }
}
