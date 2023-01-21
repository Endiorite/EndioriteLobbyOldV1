<?php

namespace packs;

interface IPackManager {

    public function inject(IPackSupplier $supplier, array $contents): void;

}