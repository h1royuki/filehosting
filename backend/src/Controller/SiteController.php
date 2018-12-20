<?php

namespace FileHosting\Controller;

use FileHosting\Infrastructure\Service\FileService;
use Slim\Http\Request;
use Slim\Http\Response;

class SiteController
{
    private $service;
    private $settings;

    public function __construct(FileService $service, array $settings)
    {
        $this->service = $service;
        $this->settings = $settings;
    }

    public function last(Request $request, Response $response): Response
    {
        $limit = $this->settings['last_limit'];

        $files = $this->service->getLastFiles($limit);

        return $response->withJson($files);
    }

    public function view(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $file = $this->service->getFileIfExist($id);

        return $response->withJson($file);
    }
}
