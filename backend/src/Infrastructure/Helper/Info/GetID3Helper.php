<?php

namespace FileHosting\Infrastructure\Helper\Info;

use FileHosting\Entity\File;
use FileHosting\Infrastructure\Helper\Info\Driver\Driver;
use FileHosting\Infrastructure\Helper\Info\Model\Archive;
use FileHosting\Infrastructure\Helper\Info\Model\Audio;
use FileHosting\Infrastructure\Helper\Info\Model\Image;
use FileHosting\Infrastructure\Helper\Info\Model\InfoModel;
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

    private function getAudioInfo(array $info): InfoModel
    {
        return (new Audio())
            ->setFormat($info['audio']['dataformat'])
            ->setBitrate($info['audio']['bitrate'])
            ->setTitle($info['tags']['id3v2']['title'][0])
            ->setAlbum($info['tags']['id3v2']['album'][0])
            ->setArtist($info['tags']['id3v2']['artist'][0]);
    }

    private function getImageInfo(array $info): InfoModel
    {
        return (new Image())
            ->setWidth($info['video']['resolution_x'])
            ->setHeight($info['video']['resolution_y'])
            ->setFormat($info['fileformat']);
    }

    private function getVideoInfo(array $info): InfoModel
    {
        return (new Video())
            ->setBitrate($info['bitrate'])
            ->setFramerate($info['video']['frame_rate'])
            ->setWidth($info['video']['resolution_x'])
            ->setHeight($info['video']['resolution_y']);
    }

    private function getArchiveInfo(array $info): InfoModel
    {
        $info = $info['zip']['files'];
        $max_items = $this->settings['archive_items'];
        $count = 0;

        $model = new Archive();

        foreach ($info as $key => $item) {
            if ($count >= $max_items) {
                break;
            }

            if (is_array($item)) {
                $model->addDirectory($key);
            } else {
                $model->addFile($key, $item);
            }

            $count++;
        }

        return $model;
    }

    private function getMD5Hash(File $file): string
    {
        return md5_file($file->getFile()->file);
    }
}
