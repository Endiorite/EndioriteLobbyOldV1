<?php

namespace endiorite\session;

use endiorite\Main;
use pocketmine\player\Player;

class Account {

    protected Player $sender;
    protected string $uuid;
    protected string $username;
    protected string $ipAdress;
    protected string $deviceOS;
    protected string $deviceId;
    protected string $lastLogin;
    protected string $firstLogin;
    protected int $playerTime;

    public function __construct(Player $sender) {
        $this->sender = $sender;
        $this->uuid = $sender->getPlayerInfo()->getUuid()->toString();
        $this->username = $sender->getName();
        $this->ipAdress = $sender->getNetworkSession()->getIp();
        $this->deviceOS = Main::$deviceOS[$this->sender->getPlayerInfo()->getExtraData()['DeviceOS']];
        $this->deviceId = $this->sender->getPlayerInfo()->getExtraData()['DeviceId'];
        $this->lastLogin = $sender->getLastPlayed();
        $this->firstLogin = $sender->getFirstPlayed();
        $this->playerTime = 0;
    }

    public function setup(): void {
        Main::getMySQL()->getConnection()->query(
            "INSERT IGNORE INTO `accounts`(`uuid`, `username`, `ip`, `deviceOS`, `client`, `lastLogin`, `firstLogin`, `playerTime`) VALUES ('{$this->uuid}','{$this->username}','{$this->ipAdress}','{$this->deviceOS}','{$this->deviceId}','{$this->lastLogin}','{$this->firstLogin}','{$this->playerTime}')"
        );
    }

    public function updateDataOnJoin(): void {
        Main::getMySQL()->getConnection()->query("UPDATE `accounts` SET `ip`='{$this->ipAdress}',`deviceOS`='{$this->deviceOS}', `lastLogin`='{$this->lastLogin}' WHERE `uuid`='{$this->uuid}'");
    }

}