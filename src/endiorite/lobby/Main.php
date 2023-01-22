<?php

namespace endiorite\lobby;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use endiorite\lobby\async\MySqlAsync;
use endiorite\lobby\commands\gamemode\gamemodeCMD;
use endiorite\lobby\commands\npc\npcCMD;
use endiorite\lobby\database\MySQL;
use endiorite\lobby\entity\FactionEntity;
use endiorite\lobby\entity\PracticeEntity;
use endiorite\lobby\Listener\PlayerManager;
use packs\ContentFactory;
use packs\PluginContent;
use packs\ResourceManager;
use packs\ResourcePackGenerator;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\World;
use ref\libNpcDialogue\libNpcDialogue;

class Main extends PluginBase {

    protected static Main $instance;
    protected static MySQL $mySQL;
    protected ResourceManager $resourceManager;

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

    protected function onLoad(): void {
        $this->resourceManager = new ResourceManager();
        ContentFactory::setFactory(fn(Plugin $plugin) => new PluginContent(
            $plugin,
            $this->resourceManager->fromPlugin($plugin)
        ));
    }

    /**
     * @throws HookAlreadyRegistered
     */
    protected function onEnable(): void {
        self::$instance = $this;
        self::$mySQL = new MySQL();

        if(!PacketHooker::isRegistered()) { PacketHooker::register($this); }
        if(!libNpcDialogue::isRegistered()) { libNpcDialogue::register($this); }

        self::getMySQL()->createTables();

        $this->disableCommands();
        $this->registerEntity();
        $this->setListener();
        $this->setCommands();
        $this->setTasks();
        $this->lockTime();
        $this->packLoader();

        $this->getLogger()->info(
            "\n \n \n§7----- §9Endiorite  Network §7-----\n \n" .
            "§l§7  *§r §fRegister CommandoAPI" . "\n" .
            "§l§7  *§r §fDatabase tables created" . "\n" .
            "§l§7  *§r §fVanilla commande disable" . "\n" .
            "§l§7  *§r §fRegister all events" . "\n" .
            "§l§7  *§r §fRegister all commands" . "\n" .
            "§l§7  *§r §fRegister all tasks" . "\n" .
            "§l§7  *§r §fRegister Entities" . "\n" .
            "§l§7  *§r §fWorlds time locked" . "\n" .
            "§l§7  *§r §fTexture pack interne" . "\n" .
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

    private function lockTime() {
        $world = $this->getServer()->getWorldManager()->getDefaultWorld();
        $world->setTime(World::TIME_DAY);
        $world->stopTime();
    }

    private function disableCommands() {
        $commands = $this->getServer()->getCommandMap();
        $list = [
            "kill", "me", "op", "deop", "enchant", "defaultgamemode",
            "difficulty", "spawnpoint", "title", "seed", "particle", "tell", "say",
            "gamemode", "time"
        ];
        foreach($list as $cmd) {
            $command = $commands->getCommand($cmd);
            $command->setLabel("old_{$command->getName()}");
            $commands->unregister($command);
        }
    }

    private function packLoader() {
        $contents = ContentFactory::getAndLock();
        $generator = new ResourcePackGenerator($this->getDataFolder() . "endiorite-lobby.zip", $this->getServer()->getMotd());
        $this->resourceManager->inject($generator, $contents);
        $pack = $generator->generate();

        $manager = $this->getServer()->getResourcePackManager();
        $reflection = new \ReflectionClass($manager);

        $packsProperty = $reflection->getProperty("resourcePacks");
        $packsProperty->setAccessible(true);

        $currentResourcePacks = $packsProperty->getValue($manager);
        $uuidProperty = $reflection->getProperty("uuidList");
        $uuidProperty->setAccessible(true);

        $currentUUIDPacks = $uuidProperty->getValue($manager);

        $property = $reflection->getProperty("serverForceResources");
        $property->setAccessible(true);
        $property->setValue($manager, true);

        $currentUUIDPacks[strtolower($pack->getPackId())] = $currentResourcePacks[] = $pack;

        $packsProperty->setValue($manager, $currentResourcePacks);
        $uuidProperty->setValue($manager, $currentUUIDPacks);
    }

    private function registerEntity() {
        EntityFactory::getInstance()->register(FactionEntity::class, function(World $world, CompoundTag $nbt): FactionEntity {
            return new FactionEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['minecraft:villager']);
        EntityFactory::getInstance()->register(PracticeEntity::class, function(World $world, CompoundTag $nbt): PracticeEntity {
            return new PracticeEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ['minecraft:blaze']);
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
        $this->getServer()->getCommandMap()->registerAll("endioritelobby", [
            new npcCMD($this, "npc", "Endiorite NPC", []),
            new gamemodeCMD($this, "gamemode", "Endiorite Gamemode", ["gm"])
        ]);
    }

    private function setTasks() {

    }

}