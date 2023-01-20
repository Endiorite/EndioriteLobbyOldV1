<?php

namespace endiorite\lobby\commands\npc\lists;

use endiorite\lobby\entity\FactionEntity;
use endiorite\lobby\entity\PracticeEntity;
use endiorite\lobby\Main;
use pocketmine\player\Player;
use pocketmine\Server;

class npcPractice {

    public function __construct(Player $sender, bool $spawned = true) {
        if($spawned) {
            $npc = new PracticeEntity($sender->getLocation());
            $npc->spawnToAll();
            $sender->sendMessage(Main::PREFIX . " §6NpcPractice §fà bien étais spawn.");
        } else {
            foreach(Server::getInstance()->getWorldManager()->getDefaultWorld()->getEntities() as $e) {
                if($e instanceof PracticeEntity) {
                    $e->close();
                    $sender->sendMessage(Main::PREFIX . " §6NpcPractice §fà bien étais supprimer.");
                }
            }
        }
    }

}