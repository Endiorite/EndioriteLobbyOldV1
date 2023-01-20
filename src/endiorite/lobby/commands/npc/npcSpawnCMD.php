<?php

namespace endiorite\lobby\commands\npc;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use endiorite\lobby\commands\npc\lists\npcFaction;
use endiorite\lobby\commands\npc\lists\npcPractice;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;

class npcSpawnCMD extends BaseSubCommand {

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare(): void {
        $this->setPermission(DefaultPermissionNames::GROUP_OPERATOR);
        $this->registerArgument(0, new RawStringArgument("nom"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $args = $args["nom"];
        if($args === "faction") {
            (new npcFaction($sender, true));
        }
        if($args === "practice") {
            (new npcPractice($sender, true));
        }
    }

}