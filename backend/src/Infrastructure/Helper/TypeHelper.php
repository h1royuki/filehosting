<?php

namespace FileHosting\Infrastructure\Helper;

use FileHosting\Entity\File;
use Slim\Http\UploadedFile;

class TypeHelper
{
    const IMAGE_MIME_TYPES = ['image/png', 'image/jpeg', 'image/gif'];
    const AUDIO_MIME_TYPES = ['audio/mpeg', 'audio/flac', 'audio/vnd.wav'];
    const VIDEO_MIME_TYPES = ['video/mp4', 'video/3gpp', 'video/quicktime', 'video/webm'];
    const ARCHIVE_MIME_TYPES = ['application/zip', 'application/x-rar-compressed', 'application/x-tar', 'application/gzip'];

    public function analyze(UploadedFile $file) : string
    {
        if ($this->isAudio($file)) {
            return File::AUDIO_TYPE;
        }

        if ($this->isImage($file)) {
            return File::IMAGE_TYPE;
        }

        if ($this->isVideo($file)) {
            return File::VIDEO_TYPE;
        }

        if ($this->isArchive($file)) {
            return File::ARCHIVE_TYPE;
        }

        return File::OTHER_TYPE;
    }

    private function isAudio(UploadedFile $file) : bool
    {
        return in_array($file->getClientMediaType(), self::AUDIO_MIME_TYPES);
    }

    private function isVideo(UploadedFile $file) : bool
    {
        return in_array($file->getClientMediaType(), self::VIDEO_MIME_TYPES);
    }

    private function isImage(UploadedFile $file): bool
    {
        return in_array($file->getClientMediaType(), self::IMAGE_MIME_TYPES);
    }

    private function isArchive(UploadedFile $file): bool
    {
        return in_array($file->getClientMediaType(), self::ARCHIVE_MIME_TYPES);
    }
}
