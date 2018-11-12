<?php

namespace FileHosting\Helper\File;

use FileHosting\Model\File;
use Slim\Http\UploadedFile;

class FileTypeHelper
{
    const IMAGE_MIME_TYPES = ['image/png', 'image/jpeg', 'image/gif'];
    const AUDIO_MIME_TYPES = ['audio/mpeg', 'audio/flac', 'audio/vnd.wav'];
    const VIDEO_MIME_TYPES = ['video/mp4', 'video/3gpp', 'video/quicktime', 'video/webm'];

    public function analyze(UploadedFile $file) : int
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
}
