<?php

namespace FileHosting\Infrastructure\Helper\Info\Driver;

use FileHosting\Entity\File;
use getID3;

class GetID3Driver implements Driver
{
    private $getID3;

    public function __construct(getID3 $getID3)
    {
        $this->getID3 = $getID3;
        $this->getID3->encoding = 'UTF-8';
        $this->getID3->option_md5_data = true;
        $this->getID3->option_md5_data_source = true;
    }

    public function getInfo(File $file): array
    {
        return $this->getID3->analyze($file->getFile()->file);
    }
}
