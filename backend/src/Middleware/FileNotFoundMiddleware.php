<?php

namespace FileHosting\Middleware;

use FileHosting\Exception\FileNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class FileNotFoundMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            return $next($request, $response);
        } catch (FileNotFoundException $e) {
            return $response
                ->withJson(['error' => $e->getMessage()])
                ->withStatus(404);
        }
    }
}
