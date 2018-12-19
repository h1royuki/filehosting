<?php

namespace FileHosting\Controller;

use FileHosting\Infrastructure\Service\FileService;
use FileHosting\Repository\FileRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class SiteController
{
    private $repository;
    private $helper;
    private $settings;

    public function __construct(FileService $helper, FileRepository $repository, array $settings)
    {
        $this->repository = $repository;
        $this->helper = $helper;
        $this->settings = $settings;
    }

    public function last(Request $request, Response $response): Response
    {
        $limit = $this->settings['last_limit'];

        $files = $this->repository->getLastFiles($limit);

        return $response->withJson($files);
    }

    public function view(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $file = $this->helper->getFileIfExist($id);

        return $response->withJson($file);
    }
}
