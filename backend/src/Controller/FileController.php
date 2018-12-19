<?php

namespace FileHosting\Controller;

use DateTime;
use FileHosting\Entity\File;
use FileHosting\Infrastructure\Service\FileService;
use FileHosting\Repository\FileRepository;
use FileHosting\Repository\SearchRepository;
use FileHosting\Validator\FileValidator;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class FileController
{
    private $validator;
    private $fileHelper;
    private $fileRepository;
    private $searchRepository;

    public function __construct(
        FileValidator $validator,
        FileService $fileHelper,
        FileRepository $fileRepository,
        SearchRepository $searchRepository
    ) {
        $this->validator = $validator;
        $this->fileHelper = $fileHelper;
        $this->fileRepository = $fileRepository;
        $this->searchRepository = $searchRepository;
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
        $file = $request->getUploadedFiles()['upload'];

        $this->validator->validate($file);

        $file = $this->deserialize($file);

        $file = $this->fileHelper->getFileType($file);
        $file = $this->fileHelper->saveFile($file);

        $this->searchRepository->indexFile($file);

        return $response->withJson($file);
    }

    public function preview(Request $request, Response $response, array $args) : Response
    {
        $id = $args['id'];

        $file = $this->fileHelper->getFileIfExist($id);

        $preview = $this->fileHelper->getPreview($file);

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
