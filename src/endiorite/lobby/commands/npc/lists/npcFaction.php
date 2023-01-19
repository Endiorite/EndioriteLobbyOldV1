<?php

namespace endiorite\lobby\commands\npc\lists;

use endiorite\lobby\entity\FactionEntity;
use endiorite\lobby\Main;
use pocketmine\player\Player;
use pocketmine\Server;

class npcFaction {

    public function __construct(Player $sender, bool $spawned = true) {
        if($spawned) {
            $npc = new FactionEntity($sender->getLocation());
            $npc->spawnToAll();
            $sender->sendMessage(Main::PREFIX . " §6NpcFaction §fà bien étais spawn.");
        } else {
            foreach(Server::getInstance()->getWorldManager()->getDefaultWorld()->getEntities() as $e) {
                if($e instanceof FactionEntity) {
                    $e->close();
                    $sender->sendMessage(Main::PREFIX . " §6NpcFaction §fà bien étais supprimer.");
                }
            }
        }
    }

}