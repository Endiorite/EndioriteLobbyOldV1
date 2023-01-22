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
        //           N       O       S                 S         E        R      V       E       U       R
        $text = "\u{E5E0}\u{E5E1}\u{E5E5}" . " " . "\u{E5E5}\u{E5D4}\u{E5E4}\u{E5E8}\u{E5D4}\u{E5E7}\u{E5E4}";
        $ui->setNpcName("$text");
        $ui->setDialogueBody(
            "Bienvenue sur notre plateforme de sélection de serveur. Veuillez sélectionner un serveur pour vous y téléporter." .
            "\n \n" .
            "Nous espérons que vous apprécierez votre expérience de jeu sur Endiorite."
        );
        $ui->setSceneName("test");
        try {
            $ui->sendTo($sender);
        } catch (\JsonException $e) {
        }
    }

}