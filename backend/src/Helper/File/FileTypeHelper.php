<?php

namespace FileHosting\Helper\File;

use Slim\Http\UploadedFile;

class FileTypeHelper
{
    const IMAGE_MIME_TYPES = ['image/png', 'image/jpeg', 'image/gif'];
    const AUDIO_MIME_TYPES = ['audio/mpeg', 'audio/flac', 'audio/vnd.wav'];
    const VIDEO_MIME_TYPES = ['video/mp4', 'video/3gpp', 'video/quicktime', 'video/webm'];

    const AUDIO_TYPE = '1';
    const VIDEO_TYPE = '2';
    const IMAGE_TYPE = '3';
    const OTHER_TYPE = '0';

    public function analyze(UploadedFile $file) : int
    {
        if ($this->isAudio($file)) {
            return self::AUDIO_TYPE;
        }

        if ($this->isImage($file)) {
            return self::IMAGE_TYPE;
        }

        if ($this->isVideo($file)) {
            return self::VIDEO_TYPE;
        }

        return self::OTHER_TYPE;
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
