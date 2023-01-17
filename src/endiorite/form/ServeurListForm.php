<?php

namespace endiorite\form;

use dktapps\pmforms\FormIcon;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class ServeurListForm extends MenuForm {

    public function __construct() {
        parent::__construct(
            "Nos serveurs",
            "",
            [
                new MenuOption("Faction\n§2Ouvert", new FormIcon("textures/items/iron_pickaxe", FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("Practice\n§cFermer", new FormIcon("textures/items/iron_sword", FormIcon::IMAGE_TYPE_PATH)),
                new MenuOption("PitchOut\n§cFermer", new FormIcon("textures/items/snowball", FormIcon::IMAGE_TYPE_PATH))
            ],
            function(Player $sender, int $selected): void {

            }
        );
    }

}