<?php

namespace packs;

class Resource {


    public function __construct(private string $inPackPath, private string $path) { }

    public function getInPackPath(): string {
        return $this->inPackPath;
    }

    public function getPath(): string {
        return $this->path;
    }

}