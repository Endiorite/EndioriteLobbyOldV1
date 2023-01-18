<?php

namespace endiorite\commands\npc;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;

class npcCMD extends BaseCommand {

    protected function prepare(): void {
        $this->setPermission(DefaultPermissionNames::GROUP_OPERATOR);
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        // TODO: Implement onRun() method.
    }
}