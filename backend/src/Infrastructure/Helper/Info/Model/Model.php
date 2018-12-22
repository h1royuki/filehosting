<?php

namespace FileHosting\Infrastructure\Helper\Info\Model;

interface Model
{
    public function getInfo(): array;

    public function fill(array $info): self;
}
