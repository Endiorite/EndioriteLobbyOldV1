<?php

namespace endiorite\lobby\session;

use endiorite\lobby\Main;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class Session {

    protected Player $sender;
    protected array $data;
    protected Config $dataGet;

    public function __construct(Player $sender) {
        $this->sender = $sender;
        $this->dataGet = new Config(Main::getInstance()->getDataFolder() . "data.yml");
        try {
            $res = Main::getMySQL()->getConnection()->query("SELECT * FROM `accounts` WHERE `uuid`='{$sender->getPlayerInfo()->getUuid()->toString()}'");
            $row = $res->fetch_array();
            if($res->num_rows > 0) {
                $this->data = [
                    "uuid" => $row["uuid"],
                    "username" => $row["username"],
                    "ip" => $row["ip"],
                    "deviceOS" => $row["deviceOS"],
                    "client" => $row["client"],
                    "lastLogin" => $row["lastLogin"],
                    "firstLogin" => $row["firstLogin"],
                    "playerTime" => $row["playerTime"]
                ];
            } else {
                $this->data = [];
            }
            $this->save();
        } catch(\mysqli_sql_exception $e) {}
    }

    private function save(): void {
        $this->dataGet->set("{$this->sender->getName()}", $this->data);
        try {
            $this->dataGet->save();
        } catch (\JsonException $e) {
        }
    }

}