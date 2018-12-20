<?php

namespace FileHosting\Controller;

use DateTime;
use FileHosting\Entity\File;
use FileHosting\Infrastructure\Service\FileService;
use FileHosting\Validator\FileValidator;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class FileController
{
    private $validator;
    private $service;

    public function __construct(
        FileValidator $validator,
        FileService $service
    ) {
        $this->validator = $validator;
        $this->service = $service;
    }

    public function download(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];

        $file = $this->service->getFileIfExist($id);

        if (!$args['stream']) {
            $file = $file->setDownloads($file->getDownloads() + 1);
        }

        $this->service->updateDownloads($file);

        $stream = $this->service->getFileStream($file);

        return $response->withBody($stream)
            ->withHeader('Content-Disposition', "attachment; filename={$file->getFilename()};")
            ->withHeader('Content-Type', FILEINFO_MIME_TYPE)
            ->withHeader('Content-Length', $file->getSize());
    }

    public function upload(Request $request, Response $response)
    {
        $file = $request->getUploadedFiles()['upload'];

        $this->validator->validate($file);

        $file = $this->deserialize($file);

        $file = $this->service->getFileType($file);
        $file = $this->service->saveFile($file);

        $this->service->indexFile($file);

        return $response->withJson($file);
    }

    public function preview(Request $request, Response $response, array $args) : Response
    {
        $id = $args['id'];

        $file = $this->service->getFileIfExist($id);

        $preview = $this->service->getPreview($file);

        $response->write($preview);

        return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);
    }

    private function deserialize(UploadedFile $file): File
    {
        return (new File())
            ->setFile($file)
            ->setFilename($file->getClientFilename())
            ->setSize($file->getSize())
            ->setDateUpload((new DateTime())->format(File::DATE_FORMAT))
            ->setDownloads(0);
    }
}
