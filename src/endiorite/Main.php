<?php

namespace endiorite;

use endiorite\database\MySQL;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    protected static Main $instance;
    protected static MySQL $mySQL;

    const PREFIX = "§l§9Endiorite §r§7»";
    const MOTD = "§l§9Endiorite §f[1.19.50]";
    const IP = "§9play.endiorite.com";
    const SITE = "www.endiorite.fr";
    const DISCORD = self::SITE . "/discord";
    const VOTE = self::SITE . "/vote";

    const DB_HOST = "endiorite.com";
    const DB_USER = "u5_g2ySo67ymX";
    const DB_PASS = "M646eXtbUZ83UP!w9bF^^=kt";
    const DB_SESSION = "s5_Poney";
    const DB_PORT = 3306;

    protected function onEnable(): void {
        self::$instance = $this;
        self::$mySQL = new MySQL();

        self::getMySQL()->createTables();

        $this->getLogger()->info(
            "\n \n \n§7----- §9Endiorite  Network §7-----\n \n" .
            "§l§7  *§r §fDatabase tables created" . "\n" .
            "\n \n"
        );
    }

    public static function getInstance(): Main{
        return self::$instance;
    }

    public static function getMySQL(): MySQL {
        return self::$mySQL;
    }

}