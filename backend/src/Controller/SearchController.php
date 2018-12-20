<?php

namespace FileHosting\Controller;

use FileHosting\Infrastructure\Service\SearchService;
use Slim\Http\Request;
use Slim\Http\Response;

class SearchController
{
    private $service;

    public function __construct(SearchService $service)
    {
        $this->service = $service;
    }

    public function search(Request $request, Response $response, array $args): Response
    {
        $query = $request->getParam('query');

        $result = $this->service->searchFiles($query);

        return $response->withJson($result);
    }
}
