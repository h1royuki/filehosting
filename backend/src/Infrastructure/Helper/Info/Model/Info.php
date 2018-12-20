<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;


interface Info
{
    public function getInfo(): array;

    public function setInfo(array $info): self;
}