<?php

namespace endiorite\lobby\particles;

use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\particle\FloatingTextParticle;
use pocketmine\world\World;

class FloatingText {

    public function __construct(Player $sender, float $x, float $y, float $z, World $world, string $text) {
        $world->addParticle(
            new Vector3($x, $y, $z),
            new FloatingTextParticle("", $text),
            [$sender]
        );
    }

}