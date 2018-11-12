<?php

namespace FileHosting\Controller;

use FileHosting\Helper\FileHelper;
use FileHosting\Repository\FileRepository;
use Slim\Http\Request;
use Slim\Http\Response;

class SiteController
{
    private $repository;
    private $helper;

    public function __construct(FileHelper $helper, FileRepository $repository)
    {
        $this->repository = $repository;
        $this->helper = $helper;
    }

    public function last(Request $request, Response $response, $args): Response
    {
        $files = $this->repository->getLastFiles(12);

        return $response->withJson($files);
    }

    public function view(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $file = $this->helper->getFileIfExist($id);

        return $response->withJson($file);
    }
}
