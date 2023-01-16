<?php

namespace endiorite\Listener;

use endiorite\Main;
use endiorite\session\Account;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\GameMode;
use pocketmine\Server;

class PlayerManager implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        $sender = $event->getPlayer();

        $sender->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
        $sender->setGamemode(GameMode::ADVENTURE());

        $account = new Account($sender);
        $account->setup();

        $sender->getInventory()->clearAll();
        $sender->getArmorInventory()->clearAll();

        $event->setJoinMessage("§a[+] {$sender->getName()}");
    }

    public function onQuit(PlayerQuitEvent $event) {
        $sender = $event->getPlayer();

        $event->setQuitMessage("§c[-] {$sender->getName()}");
    }

    public function onChat(PlayerChatEvent $event) {
        $sender = $event->getPlayer();
        $event->cancel();
        $sender->sendMessage(Main::PREFIX . "§c Le chat semble être désactivé");
    }

}