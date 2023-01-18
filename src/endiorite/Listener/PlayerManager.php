<?php

namespace endiorite\Listener;

use endiorite\form\ServeurListForm;
use endiorite\Main;
use endiorite\session\Account;
use endiorite\session\Session;
use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\data\bedrock\EffectIds;
use pocketmine\entity\effect\EffectInstance;
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
use pocketmine\network\mcpe\protocol\ToastRequestPacket;
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

        $sender->getInventory()->clearAll();
        $sender->getArmorInventory()->clearAll();

        $sender->getInventory()->setItem(0, ItemFactory::getInstance()->get(ItemIds::COMPASS)->setCustomName("§r§fServeurs"));
        $sender->getInventory()->setItem(8, ItemFactory::getInstance()->get(ItemIds::FEATHER)->setCustomName("§r§6Punch"));

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
                $sender->sendForm(new ServeurListForm());
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