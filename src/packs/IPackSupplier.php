<?php

namespace packs;

interface IPackSupplier {

    public function addFile(string $inPack, string $path): void;
    public function addFromString(string $inPack, string $content): void;

}