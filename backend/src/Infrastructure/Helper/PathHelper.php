<?php

namespace FileHosting\Infrastructure\Helper;

use DateTime;
use Exception;
use FileHosting\Entity\File;

class PathHelper
{
    const FILE_SEPARATOR = '_';
    const PATH_FORMAT_FROM_DATE = 'Y-m-d';
    const DEFAULT_FOLDER_PERMISSIONS = 0777;

    private $fileSettings;
    private $previewSettings;

    public function __construct(array $fileSettings, array $previewSettings)
    {
        $this->fileSettings = $fileSettings;
        $this->previewSettings = $previewSettings;
    }

    public function getPathToFile(File $file) : string
    {
        return  $this->getPathToFileDirectory($file).
            DIRECTORY_SEPARATOR.
            $this->getBasicFilePath($file);
    }

    public function getPathToPreview(File $file) : string
    {
        return  $this->getPathToPreviewDirectory($file).
            DIRECTORY_SEPARATOR.
            $this->getBasicFilePath($file);
    }

    public function getDefaultPreviewPath()
    {
        return $this->getPreviewPathFromConfig().
            DIRECTORY_SEPARATOR.
            $this->getDefaultPreviewPathFromConfig();
    }

    public function getPathToFileDirectory(File $file) : string
    {
        return  $this->getFilePathFromConfig().
            DIRECTORY_SEPARATOR.
            $this->getPathByFileDate($file);
    }

    public function getPathToPreviewDirectory(File $file) : string
    {
        return  $this->getPreviewPathFromConfig().
            DIRECTORY_SEPARATOR.
            $this->getPathByFileDate($file);
    }

    public function createDirectory(string $path): void
    {
        $result = mkdir($path, self::DEFAULT_FOLDER_PERMISSIONS);

        if (!$result) {
            throw new Exception('Error create directory');
        }
    }

    public function isDirectoryExist(string $path) : bool
    {
        return is_dir($path);
    }

    private function getPathByFileDate(File $file): string
    {
        $date = new DateTime($file->getDateUpload());

        return $date->format(self::PATH_FORMAT_FROM_DATE);
    }

    private function getBasicFilePath(File $file)
    {
        return $file->getId().self::FILE_SEPARATOR.$file->getFilename();
    }

    private function getFilePathFromConfig() : string
    {
        return $this->fileSettings['path'];
    }

    private function getPreviewPathFromConfig() : string
    {
        return $this->previewSettings['path'];
    }

    private function getDefaultPreviewPathFromConfig() : string
    {
        return $this->previewSettings['default'];
    }
}
