<?php

namespace endiorite\lobby\form;

use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use endiorite\lobby\Main;
use pocketmine\player\Player;
use ref\libNpcDialogue\form\NpcDialogueButtonData;
use ref\libNpcDialogue\NpcDialogue;

class ServeurListForm {

    public function __construct(Player $sender) {
        $ui = new NpcDialogue();
        $ui->addButton(NpcDialogueButtonData::create()
            ->setName("Faction")
            ->setText("Faction")
            ->setClickHandler(function(Player $sender): void {
                $sender->sendMessage(Main::PREFIX . "§f Choix du serveur faction.");
            })
            ->setForceCloseOnClick(true)
        );
        $ui->addButton(NpcDialogueButtonData::create()
            ->setName("Practice")
            ->setText("Practice")
            ->setClickHandler(function(Player $sender): void {
                $sender->sendMessage(Main::PREFIX . "§f Transfert au serveur practice.");
            })
            ->setForceCloseOnClick(true)
        );
        $ui->addButton(NpcDialogueButtonData::create()
            ->setName("Pitchout")
            ->setText("Pitchout")
            ->setClickHandler(function(Player $sender): void {
                $sender->sendMessage(Main::PREFIX . "§f Transfert au serveur pitchout...");
            })
            ->setForceCloseOnClick(true)
        );
        $ui->setNpcName("Nos serveurs");
        $ui->setDialogueBody("test body");
        $ui->setSceneName("test");
        try {
            $ui->sendTo($sender);
        } catch (\JsonException $e) {
        }
    }

}