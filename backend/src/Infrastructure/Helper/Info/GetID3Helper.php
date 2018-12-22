<?php

namespace FileHosting\Infrastructure\Helper\Info;

use FileHosting\Entity\File;
use FileHosting\Infrastructure\Helper\Info\Driver\Driver;
use FileHosting\Infrastructure\Helper\Info\Model\Archive;
use FileHosting\Infrastructure\Helper\Info\Model\Audio;
use FileHosting\Infrastructure\Helper\Info\Model\Image;
use FileHosting\Infrastructure\Helper\Info\Model\Model;
use FileHosting\Infrastructure\Helper\Info\Model\Video;

class GetID3Helper implements InfoHelper
{
    private $driver;
    private $settings;

    public function __construct(Driver $driver, $settings)
    {
        $this->driver = $driver;
        $this->settings = $settings;
    }

    public function collect(File $file): File
    {
        $info = $this->driver->getInfo($file);

        $file = $file->setHash($this->getMD5Hash($file));

        if ($file->getType() == File::AUDIO_TYPE) {
            $file->setInfo($this->getAudioInfo($info));
        }
        if ($file->getType() == File::IMAGE_TYPE) {
            $file->setInfo($this->getImageInfo($info));
        }
        if ($file->getType() == File::VIDEO_TYPE) {
            $file->setInfo($this->getVideoInfo($info));
        }
        if ($file->getType() == File::ARCHIVE_TYPE) {
            $file->setInfo($this->getArchiveInfo($info));
        }

        return $file;
    }

    private function getAudioInfo(array $info): Model
    {
        return (new Audio())->fill($info);
    }

    private function getImageInfo(array $info): Model
    {
        return (new Image())->fill($info);
    }

    private function getVideoInfo(array $info): Model
    {
        return (new Video())->fill($info);
    }

    private function getArchiveInfo(array $info): Model
    {
        $info = $info['zip']['files'];
        $max_items = $this->settings['archive_items'];

        return (new Archive($max_items))
            ->fill($info);
    }

    private function getMD5Hash(File $file): string
    {
        return md5_file($file->getFile()->file);
    }
}
