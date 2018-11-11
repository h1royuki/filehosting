<?php

namespace FileHosting\Helper\File;

use FileHosting\Model\File;
use getID3;

class GetID3Helper
{
    const AUDIO_INFO = ['dataformat', 'bitrate'];
    const AUDIO_TAGS = ['artist', 'title', 'album'];

    private $getID3;

    public function __construct(getID3 $getID3)
    {
        $this->getID3 = $getID3;
        $this->getID3->encoding = 'UTF-8';
        $this->getID3->option_md5_data = true;
        $this->getID3->option_md5_data_source = true;
    }

    public function getFileInfo(File $file): File
    {
        $path = $file->getFile()->file;

        if ($file->getType() == FileTypeHelper::AUDIO_TYPE) {
            $file->setInfo($this->getAudioInfo($path));
        }
        if ($file->getType() == FileTypeHelper::IMAGE_TYPE) {
            $file->setInfo($this->getImageInfo($path));
        }
        if ($file->getType() == FileTypeHelper::VIDEO_TYPE) {
            $file->setInfo($this->getVideoInfo($path));
        }

        return $file;
    }

    private function getAudioInfo(string $path): array
    {
        $info = [];

        $getid3 = $this->getID3->analyze($path);

        foreach ($getid3['audio'] as $key => $value) {
            if (in_array($key, self::AUDIO_INFO)) {
                $info[$key] = $value;
            }
        }

        foreach ($getid3['tags']['id3v2'] as $key => $value) {
            if (in_array($key, self::AUDIO_TAGS)) {
                $info[$key] = $value[0];
            }
        }

        return $info;
    }

    private function getImageInfo(string  $path): array
    {
        $info = [];

        $getid3 = $this->getID3->analyze($path);

        $info['width'] = $getid3['video']['resolution_x'];
        $info['height'] = $getid3['video']['resolution_y'];
        $info['format'] = $getid3['fileformat'];

        return $info;
    }

    private function getVideoInfo(string $path): array
    {
        $info = [];

        $getid3 = $this->getID3->analyze($path);

        $info['bitrate'] = $getid3['bitrate'];
        $info['framerate'] = $getid3['video']['frame_rate'];
        $info['width'] = $getid3['video']['resolution_x'];
        $info['height'] = $getid3['video']['resolution_y'];

        return $info;
    }
}
