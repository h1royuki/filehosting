<?php

namespace FileHosting\Middleware;

use FileHosting\Exception\ValidationException;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidationMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        try {
            return $next($request, $response);
        } catch (ValidationException $e) {
            return $response
                ->withJson(['errors' => $e->getErrors()])
                ->withStatus(400);
        }
    }
}
