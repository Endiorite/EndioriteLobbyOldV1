<?php

namespace endiorite;

use endiorite\async\MySqlAsync;
use endiorite\database\MySQL;
use endiorite\entity\FactionEntity;
use endiorite\Listener\PlayerManager;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\World;

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

    public static array $deviceOS = [
        -1 => "Unknown", 1 => "Android", 2 => "IOS", 3 => "MacOS", 4 => "FireOS",
        5 => "VRGear", 6 => "VRHololens", 7 => "Windows", 8 => "Windows", 9 => "Dedicated",
        10 => "tvOS", 11 => "PS4", 12 => "Nintendo Switch", 13 => "Xbox",
        20 => "Linux"
    ];

    protected function onEnable(): void {
        self::$instance = $this;
        self::$mySQL = new MySQL();

        self::getMySQL()->createTables();

        $this->disableCommands();
        $this->registerEntity();
        $this->setListener();
        $this->setCommands();
        $this->setTasks();

        $this->getLogger()->info(
            "\n \n \n§7----- §9Endiorite  Network §7-----\n \n" .
            "§l§7  *§r §fDatabase tables created" . "\n" .
            "§l§7  *§r §fVanilla commande disable" . "\n" .
            "§l§7  *§r §fRegister all events" . "\n" .
            "§l§7  *§r §fRegister all commands" . "\n" .
            "§l§7  *§r §fRegister all tasks" . "\n" .
            "§l§7  *§r §fRegister Entities" . "\n" .
            "\n \n"
        );

        $this->getServer()->getNetwork()->setName(self::MOTD);
    }

    public static function getInstance(): Main{
        return self::$instance;
    }

    public static function getMySQL(): MySQL {
        return self::$mySQL;
    }

    public static function sendMySqlAsync(string $query) {
        self::getInstance()->getServer()->getAsyncPool()->submitTask(new MySqlAsync($query));
    }

    public static function getSessionData(): Config {
        return new Config(self::getInstance()->getDataFolder() . "data.yml");
    }

    private function disableCommands() {
        $commands = $this->getServer()->getCommandMap();
        $list = [
            "kill", "me", "op", "deop", "enchant", "defaultgamemode",
            "difficulty", "spawnpoint", "title", "seed", "particle", "tell", "say",
            "gamemode"
        ];
        foreach($list as $cmd) {
            $command = $commands->getCommand($cmd);
            $command->setLabel("old_{$command->getName()}");
            $commands->unregister($command);
        }
    }

    private function registerEntity() {
        EntityFactory::getInstance()->register(FactionEntity::class, function(World $world, CompoundTag $nbt): FactionEntity {
            return new FactionEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['minecraft:villager']);
    }

    private function setListener() {
        $list = [
            new PlayerManager()
        ];
        foreach($list as $events) {
            $this->getServer()->getPluginManager()->registerEvents($events, $this);
        }
    }

    private function setCommands() {

    }

    private function setTasks() {

    }

}