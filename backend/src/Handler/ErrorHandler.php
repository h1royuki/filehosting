<?php

namespace FileHosting\Handler;

use FileHosting\Exception\FileNotFoundException;
use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorHandler
{
    private $logger;
    private $notFoundMiddleware;

    public function __construct(Logger $logger, NotFoundHandler $notFoundMiddleware)
    {
        $this->logger = $logger;
        $this->notFoundMiddleware = $notFoundMiddleware;
    }

    public function __invoke(Request $request, Response $response, \Exception $exception): Response
    {
        if ($exception instanceof FileNotFoundException) {
            return ($this->notFoundMiddleware)($request, $response);
        }

        $this->logger->critical($exception->getMessage(), $exception->getTrace());

        return $response
            ->withStatus(500)
            ->withJson(['error' => $exception->getMessage()]);
    }
}
