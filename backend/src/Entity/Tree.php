<?php

namespace FileHosting\Entity;

use JsonSerializable;

class Tree implements JsonSerializable
{
    private $parent;
    private $childs = [];

    public function __construct(Comment $parent)
    {
        $this->parent = $parent;
    }

    public function jsonSerialize() : array
    {
        return [
            'parent' => $this->getParent(),
            'childs' => $this->getChilds(),
        ];
    }

    public function addChild($comment) : self
    {
        $this->childs[] = $comment;

        return $this;
    }

    public function getParent() : ?Comment
    {
        return $this->parent;
    }

    public function getChilds() : array
    {
        return $this->childs;
    }

    public function setParent(Comment $parent) : self
    {
        $this->parent = $parent;

        return $this;
    }
}
