<?php

namespace FileHosting\Controller;

use FileHosting\Helper\File\GetID3Helper;
use FileHosting\Helper\FileHelper;
use FileHosting\Model\File;
use FileHosting\Repository\FileRepository;
use FileHosting\Repository\SearchRepository;
use FileHosting\Validator\FileValidator;
use Slim\Http\Request;
use Slim\Http\Response;

class FileController
{
    private $validator;
    private $fileHelper;
    private $fileRepository;
    private $searchRepository;
    private $getID3Helper;

    public function __construct(
        FileValidator $validator,
        FileHelper $fileHelper,
        FileRepository $fileRepository,
        SearchRepository $searchRepository,
        GetID3Helper $getID3Helper
    ) {
        $this->validator = $validator;
        $this->fileHelper = $fileHelper;
        $this->fileRepository = $fileRepository;
        $this->searchRepository = $searchRepository;
        $this->getID3Helper = $getID3Helper;
    }

    public function download(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $file = $this->fileHelper->getFileIfExist($id);

        if (!$args['stream']) {
            $file = $file->setDownloads($file->getDownloads() + 1);
        }

        $this->fileRepository->updateDownloads($file);
        $stream = $this->fileHelper->getFileStream($file);

        return $response->withBody($stream)
            ->withHeader('Content-Disposition', "attachment; filename={$file->getFilename()};")
            ->withHeader('Content-Type', FILEINFO_MIME_TYPE)
            ->withHeader('Content-Length', $file->getSize());
    }

    public function upload(Request $request, Response $response)
    {
        $uploadedFile = $request->getUploadedFiles()['upload'];
        $this->validator->validate($uploadedFile);

        $file = new File();
        $file = $this->fileHelper->parseRequest($uploadedFile, $file);
        $file = $this->fileHelper->getFileType($file);
        $file = $this->getID3Helper->getFileInfo($file);
        $file = $this->fileHelper->saveFile($file);
        $this->searchRepository->indexFile($file);


        return $response->withJson(['id' => $file->getId()]);
    }

    public function preview(Request $request, Response $response, array $args) : Response
    {
        $id = $args['id'];

        $file = $this->fileHelper->getFileIfExist($id);

        $preview = $this->fileHelper->getPreview($file);

        $response->write($preview);

        return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);
    }
}
