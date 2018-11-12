<?php

namespace FileHosting\Handler;

use FileHosting\Exception\FileNotFoundException;
use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorHandler
{
    private $logger;
    private $notFoundHandler;

    public function __construct(Logger $logger, NotFoundHandler $notFoundHandler)
    {
        $this->logger = $logger;
        $this->notFoundHandler = $notFoundHandler;
    }

    public function __invoke(Request $request, Response $response, \Exception $exception): Response
    {
        if ($exception instanceof FileNotFoundException) {
            return ($this->notFoundHandler)($request, $response);
        }

        $this->logger->critical($exception->getMessage(), $exception->getTrace());

        return $response
            ->withStatus(500)
            ->withJson(['error' => $exception->getMessage()]);
    }
}
