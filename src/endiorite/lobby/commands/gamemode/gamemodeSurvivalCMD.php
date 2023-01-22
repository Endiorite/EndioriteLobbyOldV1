<?php

namespace endiorite\lobby\commands\gamemode;

use CortexPE\Commando\BaseSubCommand;
use endiorite\lobby\Main;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class gamemodeSurvivalCMD extends BaseSubCommand {

    protected function prepare(): void {
        $this->setPermission(DefaultPermissionNames::GROUP_OPERATOR);
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        if(!$sender instanceof Player) {
            return;
        }
        $sender->sendMessage(Main::PREFIX . "§f Mode de jeu définie sur §3Survival§f.");
        $sender->setGamemode(GameMode::SURVIVAL());
    }
}