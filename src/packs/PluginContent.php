<?php

namespace packs;

use pocketmine\plugin\Plugin;

class PluginContent {

    public function __construct(private Plugin $plugin, private PluginResources $resources) {}

    public function getPlugin(): Plugin {
        return $this->plugin;
    }

    public function getResources(): PluginResources {
        return $this->resources;
    }

}