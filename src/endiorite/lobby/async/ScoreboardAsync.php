<?php

namespace endiorite\lobby\async;

use endiorite\lobby\Main;
use pocketmine\player\Player;
use pocketmine\scheduler\AsyncTask;

class ScoreboardAsync extends AsyncTask {

    protected Player $sender;

    public function __construct(Player $sender) {
        $this->sender = $sender;
    }

    public function onRun(): void {

    }

}