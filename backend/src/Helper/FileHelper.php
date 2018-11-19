<?php

namespace FileHosting\Helper;

use DateTime;
use Exception;
use FileHosting\Exception\FileNotFoundException;
use FileHosting\Helper\File\FileTypeHelper;
use FileHosting\Helper\File\PathHelper;
use FileHosting\Helper\File\PreviewHelper;
use FileHosting\Model\File;
use FileHosting\Repository\FileRepository;
use Slim\Http\Stream;
use Slim\Http\UploadedFile;

class FileHelper
{
    private $pathHelper;
    private $repository;
    private $previewHelper;

    public function __construct(PreviewHelper $previewHelper, FileRepository $repository, PathHelper $pathHelper)
    {
        $this->previewHelper = $previewHelper;
        $this->repository = $repository;
        $this->pathHelper = $pathHelper;
    }

    public function saveFile(File $file): File
    {
        $this->repository->beginTransaction();
        $file = $this->repository->addNewFile($file);

        try {
            $filePath = $this->pathHelper->getPathToFile($file);

            $file = $this->moveFile($file, $filePath);
            $file = $this->createPreview($file, $filePath);

            $this->repository->commitTransaction();

            return $file;
        } catch (Exception $e) {
            $this->repository->abortTransaction();

            throw new Exception($e->getMessage());
        }
    }

    public function createPreview(File $file, string $filePath): File
    {
        if ($file->getType() != File::IMAGE_TYPE) {
            return $file;
        }

        $directory = $this->pathHelper->getPathToPreviewDirectory($file);
        $path = $this->pathHelper->getPathToPreview($file);

        if (!$this->pathHelper->isDirectoryExist($directory)) {
            $this->pathHelper->createDirectory($directory);
        }

        $this->previewHelper->generateThumbnail($filePath, $path);

        return $file;
    }

    public function getFileType(File $file): File
    {
        $typeHelper = new FileTypeHelper();
        $type = $typeHelper->analyze($file->getFile());
        $file = $file->setType($type);

        return $file;
    }

    public function moveFile(File $file, $path) : File
    {
        $directory = $this->pathHelper->getPathToFileDirectory($file);

        if (!$this->pathHelper->isDirectoryExist($directory)) {
            $this->pathHelper->createDirectory($directory);
        }

        $file->getFile()->moveTo($path);

        return $file;
    }

    public function getPreview(File $file) : string
    {
        $path = $this->pathHelper->getPathToPreview($file);

        if ($file->getType() != File::IMAGE_TYPE || !file_exists($path)) {
            $path = $this->pathHelper->getDefaultPreviewPath();
        }

        return file_get_contents($path);
    }

    public function getFileStream(File $file) : Stream
    {
        $path = $this->pathHelper->getPathToFile($file);

        $fh = fopen($path, 'rb');

        return new Stream($fh);
    }

    public function getFileIfExist($id) :File
    {
        if (!is_int($id)) {
            throw new Exception('File ID must be a integer');
        }
        
        $file = $this->repository->getFileById($id);

        if (!$file) {
            throw new FileNotFoundException('File not found');
        }

        $path = $this->pathHelper->getPathToFile($file);

        if (!file_exists($path)) {
            throw new Exception('File not available');
        }

        return $file;
    }

    public function parseRequest(UploadedFile $uploaded, File $file) : File
    {
        $date = new DateTime();

        return $file
            ->setFile($uploaded)
            ->setFilename($uploaded->getClientFilename())
            ->setSize($uploaded->getSize())
            ->setDateUpload($date->format(File::DATE_FORMAT))
            ->setDownloads(0);
    }
}
