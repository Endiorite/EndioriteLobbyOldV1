<?php

namespace endiorite\lobby\commands\gamemode;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;

class gamemodeCMD extends BaseCommand {

    protected function prepare(): void {
        $this->setPermission(DefaultPermissionNames::GROUP_OPERATOR);
        $this->registerSubCommand(new gamemodeCreativeCMD("c", "gamemode endiorite"));
        $this->registerSubCommand(new gamemodeSurvivalCMD("s", "gamemode endiorite"));
        $this->registerSubCommand(new gamemodeAdventureCMD("a", "gamemode endiorite"));
        $this->registerSubCommand(new gamemodeSpectatorCMD("spec", "gamemode endiorite"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        // TODO: Implement onRun() method.
    }
}