<?php

namespace FileHosting\Helper\File;

use FileHosting\Model\File;

class PathHelper
{
    const FILE_SEPARATOR = '_';

    private $fileSettings;
    private $previewSettings;

    public function __construct(array $fileSettings, array $previewSettings)
    {
        $this->fileSettings = $fileSettings;
        $this->previewSettings = $previewSettings;
    }

    public function generatePathToFile(File $file) : string
    {
        return  $this->getFilePath().
            DIRECTORY_SEPARATOR.
            $file->getHash().
            self::FILE_SEPARATOR.
            $file->getFilename();
    }

    public function generatePathToPreview(File $file) : string
    {
        return  $this->getPreviewPath().
            DIRECTORY_SEPARATOR.
            $file->getHash().
            self::FILE_SEPARATOR.
            $file->getFilename();
    }

    public function getDefaultPreviewPath()
    {
        return $this->getPreviewPath().DIRECTORY_SEPARATOR.$this->getDefaultPreviewName();
    }

    private function getFilePath() : string
    {
        return $this->fileSettings['path'];
    }

    private function getPreviewPath() : string
    {
        return $this->previewSettings['path'];
    }

    private function getDefaultPreviewName()
    {
        return $this->previewSettings['default'];
    }
}
