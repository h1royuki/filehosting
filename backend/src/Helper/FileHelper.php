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

    public function parseRequest(UploadedFile $uploaded, File $file) : File
    {
        $date = new DateTime();

        return $file
            ->setFile($uploaded)
            ->setFilename($uploaded->getClientFilename())
            ->setSize($uploaded->getSize())
            ->setDateUpload($date->getTimestamp())
            ->setDownloads(0)
            ->setHash($this->generateHash());
    }

    public function saveFile(File $file): File
    {
        $this->repository->beginTransaction();
        $id = $this->repository->addNewFile($file);

        try {
            $filePath = $this->pathHelper->generatePathToFile($file);

            $this->moveFile($file, $filePath);

            if ($file->getType() == FileTypeHelper::IMAGE_TYPE) {
                $previewPath = $this->pathHelper->generatePathToPreview($file);
                $this->previewHelper->generateThumbnail($filePath, $previewPath);
            }

            $this->repository->commitTransaction();
            $file->setId($id);

            return $file;
        } catch (Exception $e) {
            $this->repository->abortTransaction();

            throw new Exception('Error saving file');
        }
    }

    public function getFileType(File $file): File
    {
        $typeHelper = new FileTypeHelper();
        $type = $typeHelper->analyze($file->getFile());
        $file = $file->setType($type);

        return $file;
    }

    public function moveFile(File $file, $path) : void
    {
        $file->getFile()->moveTo($path);
    }

    public function getPreview(File $file) : string
    {
        $path = $this->pathHelper->generatePathToPreview($file);

        if ($file->getType() != FileTypeHelper::IMAGE_TYPE || !file_exists($path)) {
            $path = $this->pathHelper->getDefaultPreviewPath();
        }

        return file_get_contents($path);
    }

    public function getFileStream(File $file) : Stream
    {
        $path = $this->pathHelper->generatePathToFile($file);

        $fh = fopen($path, 'rb');

        return new Stream($fh);
    }

    private function generateHash()
    {
        return bin2hex(random_bytes(8));
    }

    public function getFileIfExist(int $id) :File
    {
        $file = $this->repository->getFileById($id);

        if (!$file) {
            throw new FileNotFoundException();
        }

        $path = $this->pathHelper->generatePathToFile($file);

        if (!file_exists($path)) {
            throw new Exception('File not available');
        }

        return $file;
    }
}
