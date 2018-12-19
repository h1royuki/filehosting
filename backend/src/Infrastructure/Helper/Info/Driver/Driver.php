<?php

namespace FileHosting\Infrastructure\Helper\Info\Driver;

use FileHosting\Entity\File;

interface Driver
{

    public function getInfo(File $file): array;

}
