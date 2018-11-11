<?php

namespace FileHosting\Validator;

use Slim\Http\UploadedFile;

class FileValidator
{
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function validate(UploadedFile $file = null) : void
    {
        if (!$file) {
            throw new \Exception('File not attached');
        }

        if (empty($file->getClientFilename())) {
            throw new \Exception('Unknown file name');
        }

        if ($file->getSize() > $this->settings['max_size']) {
            throw new \Exception('File size too big');
        }
    }
}
