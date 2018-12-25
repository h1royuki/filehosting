<?php

namespace FileHosting\Infrastructure\Service;

use Exception;
use FileHosting\Entity\File;
use FileHosting\Exception\FileNotFoundException;
use FileHosting\Infrastructure\Helper\Info\InfoHelper;
use FileHosting\Infrastructure\Helper\PathHelper;
use FileHosting\Infrastructure\Helper\PreviewHelper;
use FileHosting\Infrastructure\Helper\TypeHelper;
use FileHosting\Repository\FileRepository;
use FileHosting\Repository\SearchRepository;
use Slim\Http\Stream;

class FileService
{
    private $pathHelper;
    private $fileRepository;
    private $searchRepository;
    private $previewHelper;
    private $infoHelper;

    public function __construct(
        InfoHelper $infoHelper,
        PreviewHelper $previewHelper,
        PathHelper $pathHelper,
        FileRepository $fileRepository,
        SearchRepository $searchRepository
    ) {
        $this->infoHelper = $infoHelper;
        $this->previewHelper = $previewHelper;
        $this->fileRepository = $fileRepository;
        $this->pathHelper = $pathHelper;
        $this->searchRepository = $searchRepository;
    }

    public function saveFile(File $file): File
    {
        $this->fileRepository->beginTransaction();
        $file = $this->infoHelper->collect($file);
        $file = $this->fileRepository->addNewFile($file);

        try {
            $filePath = $this->pathHelper->getPathToFile($file);

            $file = $this->moveFile($file, $filePath);
            $file = $this->createPreview($file, $filePath);

            $this->fileRepository->commitTransaction();

            return $file;
        } catch (Exception $e) {
            $this->fileRepository->abortTransaction();

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
        $typeHelper = new TypeHelper();
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

    public function getFileIfExist(string $id) : File
    {
        if (!is_numeric($id)) {
            throw new Exception('File ID must be a integer');
        }

        $file = $this->fileRepository->getFileById($id);

        if (!$file) {
            throw new FileNotFoundException('File not found');
        }

        $path = $this->pathHelper->getPathToFile($file);

        if (!file_exists($path)) {
            throw new Exception('File not available');
        }

        return $file;
    }

    public function updateDownloads(File $file): bool
    {
        return $this->fileRepository->updateDownloads($file);
    }

    public function indexFile(File $file): bool
    {
        return $this->searchRepository->indexFile($file);
    }

    public function getLastFiles(int $count): array
    {
        return $this->fileRepository->getLastFiles($count);
    }
}
