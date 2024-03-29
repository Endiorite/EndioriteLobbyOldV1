<?php

namespace packs;

use pocketmine\plugin\Plugin;

class ContentFactory {

    private static ?\Closure $factory;
    private static ?array $contents = [];

    public static function setFactory(\Closure $factory): void {
        self::$factory = $factory;
    }

    public static function create(Plugin $owner): PluginContent {
        if(self::$contents === null || self::$factory === null) {
            throw new \LogicException("You can use ContentFactory::create a only between PinkPack loader and enabling");
        }
        return self::$contents[$owner->getName()] ?? self::$contents[$owner->getName()] = (self::$factory)($owner);
    }

    public static function getAndLock(): array {
        try {
            return self::$contents;
        } finally {
            self::$contents = null;
            self::$factory = null;
        }
    }

}