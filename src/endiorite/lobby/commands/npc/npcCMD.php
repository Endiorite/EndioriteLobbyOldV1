<?php

namespace endiorite\lobby\commands\npc;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;

class npcCMD extends BaseCommand {

    protected function prepare(): void {
        $this->setPermission(DefaultPermissionNames::GROUP_OPERATOR);
        $this->registerSubCommand(new npcSpawnCMD("spawn", "endiorite npc", []));
        $this->registerSubCommand(new npcRemoveCMD("remove", "endiorite npc", []));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        // TODO: Implement onRun() method.
    }
}