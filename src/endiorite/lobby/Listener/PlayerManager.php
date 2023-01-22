<?php

namespace endiorite\lobby\Listener;

use endiorite\lobby\async\ScoreboardAsync;
use endiorite\lobby\form\ServeurListForm;
use endiorite\lobby\Main;
use endiorite\lobby\particles\FloatingText;
use endiorite\lobby\session\Account;
use endiorite\lobby\session\Session;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\player\GameMode;
use pocketmine\Server;

class PlayerManager implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        $sender = $event->getPlayer();

        $sender->teleport(Server::getInstance()->getWorldManager()->getDefaultWorld()->getSpawnLocation());
        $sender->setGamemode(GameMode::ADVENTURE());

        if(!$sender->hasPlayedBefore()) {
            $sender->sendMessage(Main::PREFIX . "§f Création de votre profil en cours...");
            $account = new Account($sender);
            $account->setup();
            $sender->sendMessage(Main::PREFIX . "§f Votre profil à étais crée avec succès§!");
        }
        $sender->sendMessage(Main::PREFIX . "§f Chargement de vos donnée...");
        $account = new Account($sender);
        $account->updateDataOnJoin();
        (new Session($sender));
        $sender->sendMessage(Main::PREFIX . "§f Vos données sont chargées, bon jeu sur §l§9Endiorite§r§f.");

        $scoreboardApi = Main::getScoreboardAPI();
        $scoreboardApi->new($sender, "ObjectiveName", "Endiorite Network");
        $scoreboardApi->setLine($sender, 1, "§c  ");
        $scoreboardApi->setLine($sender, 2, "§l§7»§r §3{$sender->getName()}");
        $scoreboardApi->setLine($sender, 3, "§f Grade: §6{rank.name}");
        $scoreboardApi->setLine($sender, 4, "§f ");
        $scoreboardApi->setLine($sender, 5, "§bplay.endiorite.com");

        $sender->getInventory()->clearAll();
        $sender->getArmorInventory()->clearAll();

        $sender->getInventory()->setItem(0, ItemFactory::getInstance()->get(ItemIds::COMPASS)->setCustomName("§r§fServeurs"));
        $sender->getInventory()->setItem(8, ItemFactory::getInstance()->get(ItemIds::FEATHER)->setCustomName("§r§6Punch"));

        (new FloatingText(
            $sender,
            -145.5,
            79.5,
            -76.5,
            Main::getInstance()->getServer()->getWorldManager()->getDefaultWorld(),
            "§7" . "-----------" . "\n" .
                 "§l§9" . "Bienvenue sur Endiorite§r" . "\n \n" .
                 "§f" . "Vous êtes actuellement sur le lobby." . "\n" .
                 "§f" . "Frappe un NPC pour rejoindre un serveur." . "\n" .
                 "§7" . "-----------"
        ));

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

    public function onChangeSlot(InventoryTransactionEvent $event) {
        $sender = $event->getTransaction()->getSource();
        if($sender->getGamemode() !== GameMode::CREATIVE()) {
            $event->cancel();
        }
    }

    public function onDamage(EntityDamageEvent $event) {
        $event->cancel();
    }

    public function onFood(PlayerExhaustEvent $event) {
        $event->cancel();
    }

    protected array $cooldown = [];
    public function onUse(PlayerItemUseEvent $event) {
        $sender = $event->getPlayer();
        $item = $sender->getInventory()->getItemInHand();
        if(time() < ($this->cooldown[$sender->getId()] ?? 0)) {
            return true;
        } else {
            if($item->getId() === ItemIds::COMPASS) {
                (new ServeurListForm($sender));
                //$sender->sendForm(new ServeurListForm());
                $this->cooldown[$sender->getId()] = time() + 2;
                return true;
            }
            if($item->getId() === ItemIds::FEATHER) {
                $direction = $sender->getDirectionPlane()->normalize()->multiply(1);
                $sender->setMotion(new Vector3($direction->getX(), 1, $direction->getY()));
                $this->cooldown[$sender->getId()] = time() + 1;
                return true;
            }

        }
    }

}