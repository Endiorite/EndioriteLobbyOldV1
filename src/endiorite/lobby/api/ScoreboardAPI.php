<?php

namespace endiorite\lobby\api;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;

class ScoreboardAPI {

    public array $lists = [];

    public function getObjectiveName(Player $sender): ?string {
        return $this->lists[$sender->getName()] ?? null;
    }

    public function remove(Player $sender): void {
        $sender->getNetworkSession()->sendDataPacket(RemoveObjectivePacket::create(
            $this->getObjectiveName($sender)
        ));
        unset($this->lists[$sender->getName()]);
    }

    public function new(Player $sender, string $obj, string $display): void {
        if(isset($this->lists[$sender->getName()])) { $this->remove($sender); }
        $sender->getNetworkSession()->sendDataPacket(SetDisplayObjectivePacket::create(
            "sidebar",
            $obj,
            $display,
            "dummy",
            0
        ));
        $this->lists[$sender->getName()] = $obj;
    }

    public function setLine(Player $sender, int $score, string $message): void {
        if(!isset($this->lists[$sender->getName()])) { return; }
        if($score > 15 || $score < 1) { return; }

        $e = new ScorePacketEntry();
        $e->objectiveName = $this->getObjectiveName($sender);
        $e->type = $e::TYPE_FAKE_PLAYER;
        $e->customName = $message;
        $e->score = $score;
        $e->scoreboardId = $score;

        $pk = new SetScorePacket();
        $pk->type = $pk::TYPE_CHANGE;
        $pk->entries[] = $e;

        $sender->getNetworkSession()->sendDataPacket($pk);
    }

}