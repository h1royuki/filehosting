<?php

namespace FileHosting\Helper\File;

use FileHosting\Model\File;
use getID3;

class GetID3Helper
{
    const AUDIO_INFO = ['dataformat', 'bitrate'];
    const AUDIO_TAGS = ['artist', 'title', 'album'];

    private $getID3;
    private $settings;

    public function __construct(getID3 $getID3, $settings)
    {
        $this->getID3 = $getID3;
        $this->settings = $settings;

        $this->getID3->encoding = 'UTF-8';
        $this->getID3->option_md5_data = true;
        $this->getID3->option_md5_data_source = true;
    }

    public function getFileInfo(File $file): File
    {
        $path = $file->getFile()->file;
        $getid3 = $this->getID3->analyze($path);

        $md5 = $this->getMD5Hash($getid3, $path);

        $file = $file->setHash($md5);

        if ($file->getType() == File::AUDIO_TYPE) {
            $file->setInfo($this->getAudioInfo($getid3));
        }
        if ($file->getType() == File::IMAGE_TYPE) {
            $file->setInfo($this->getImageInfo($getid3));
        }
        if ($file->getType() == File::VIDEO_TYPE) {
            $file->setInfo($this->getVideoInfo($getid3));
        }
        if ($file->getType() == File::ARCHIVE_TYPE) {
            $file->setInfo($this->getArchiveInfo($getid3));
        }

        return $file;
    }

    private function getAudioInfo(array $getID3): array
    {
        $info = [];

        foreach ($getID3['audio'] as $key => $value) {
            if (in_array($key, self::AUDIO_INFO)) {
                $info[$key] = $value;
            }
        }

        foreach ($getID3['tags']['id3v2'] as $key => $value) {
            if (in_array($key, self::AUDIO_TAGS)) {
                $info[$key] = $value[0];
            }
        }

        return $info;
    }

    private function getImageInfo(array $getID3): array
    {
        $info = [];

        $info['width'] = $getID3['video']['resolution_x'];
        $info['height'] = $getID3['video']['resolution_y'];
        $info['format'] = $getID3['fileformat'];

        return $info;
    }

    private function getVideoInfo(array $getID3): array
    {
        $info = [];

        $info['bitrate'] = $getID3['bitrate'];
        $info['framerate'] = $getID3['video']['frame_rate'];
        $info['width'] = $getID3['video']['resolution_x'];
        $info['height'] = $getID3['video']['resolution_y'];

        return $info;
    }

    private function getArchiveInfo(array $getid3): array
    {
        $items = $getid3['zip']['files'];

        $max_items = $this->settings['archive_items'];
        $count = 0;

        foreach ($items as $key => $item) {
            if ($count >= $max_items) {
                break;
            }

            if (is_array($item)) {
                $info['directories'][] = $key;
            } else {
                $info['files'][$key] = $item;
            }

            $count++;
        }

        return $info;
    }

    private function getMD5Hash(array $getID3, string $path): string
    {
        return $getID3['md5_data'] ? $getID3['md5_data'] : md5_file($path);
    }
}
