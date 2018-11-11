<?php

namespace FileHosting\Controller;

use FileHosting\Helper\SearchHelper;
use Slim\Http\Request;
use Slim\Http\Response;

class SearchController
{
    private $searchHelper;

    public function __construct(SearchHelper $searchHelper)
    {
        $this->searchHelper = $searchHelper;
    }

    public function search(Request $request, Response $response, array $args): Response
    {
        $query = $args['query'];

        $result = $this->searchHelper->searchFiles($query);

        return $response->withJson($result);
    }
}
