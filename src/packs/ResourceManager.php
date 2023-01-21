<?php

namespace packs;

use pocketmine\plugin\Plugin;

class ResourceManager implements IPackManager {

    public function fromPlugin(Plugin $plugin): PluginResources {
        return new PluginResources($plugin);
    }

    public function inject(IPackSupplier $supplier, array $contents): void {
        foreach ($contents as $content) {
            foreach ($content->getResources()->getAll() as $resource) {
                $supplier->addFile($resource->getInPackPath(), $resource->getPath());
            }
        }
    }

}