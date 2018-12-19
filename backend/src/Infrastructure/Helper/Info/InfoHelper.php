<?php

namespace FileHosting\Infrastructure\Helper\Info;


use FileHosting\Entity\File;
use FileHosting\Infrastructure\Helper\Info\Driver\Driver;

interface InfoHelper
{

    public function __construct(Driver $driver, array $settings);

    public function collect(File $file): File;

}