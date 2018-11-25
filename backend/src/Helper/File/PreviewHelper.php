<?php

namespace FileHosting\Helper\File;

use Exception;
use Imagick;

class PreviewHelper
{
    const GIF_MIME_TYPE = 'image/gif';

    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function generateThumbnail(string $filePath, string $previewPath): void
    {
        try {
            $image = new Imagick($filePath);

            if ($this->isGif($image)) {
                $image = $this->generateFromGif($image);
            } else {
                $image = $this->generateFromStaticImage($image);
            }

            $image->writeImage($previewPath);
        } catch (Exception $e) {
            return;
        }
    }

    private function generateFromGif(Imagick $image): Imagick
    {
        $image = $image->coalesceImages();

        foreach ($image as $frame) {
            $frame->thumbnailImage($this->getThumbnailWidth(), $this->getThumbnailHeight());

            return $frame;
        }
    }

    private function generateFromStaticImage(Imagick $image): Imagick
    {
        $image->thumbnailImage($this->getThumbnailWidth(), $this->getThumbnailHeight());

        return $image;
    }

    private function getThumbnailWidth(): int
    {
        return $this->settings['width'];
    }

    private function getThumbnailHeight(): int
    {
        return $this->settings['height'];
    }

    private function isGif(Imagick $image): bool
    {
        return $image->getImageMimeType() == self::GIF_MIME_TYPE;
    }
}
