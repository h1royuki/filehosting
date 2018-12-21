<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;


interface Info
{
    public function getInfo(): array;

    public function fill(array $info): self;
}