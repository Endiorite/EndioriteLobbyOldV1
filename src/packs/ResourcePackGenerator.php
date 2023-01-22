<?php

namespace packs;

use pocketmine\resourcepacks\ResourcePack;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use Ramsey\Uuid\Uuid;

class ResourcePackGenerator implements IPackSupplier {

    private const UUID_PACK_NAMESPACE = "3c9f34c3-279b-4c63-9685-d68a70d632fe";
    private const UUID_RESOURCE_NAMESPACE = "389a36f8-c036-4cd2-a990-deecacea6382";

    protected string $pathFolder = "/plugins/EndioriteLobby/packs/";

    private \ZipArchive $archive;
    private string $checkSumSource = "";

    public function __construct(private string $path, private string $name) {
        @unlink($this->path);
        $this->archive = new \ZipArchive();
        $this->archive->open($this->path, \ZipArchive::CREATE);
        foreach([
            "font",
            "ui",
            "textures",
            "textures/ui"
                ] as $folder) {
            $this->archive->addEmptyDir($folder);
        }
        foreach([
            "font/glyph_E5.png",
            "ui/npc_interact_screen.json",
            "ui/scoreboard.json",
            "textures/ui/banner.png",
            "textures/ui/Black_sb.png",
            "textures/ui/Black_sb.json",
            "textures/ui/dialogue_background.png"
                ] as $file) {
            $this->archive->addFile(
                Server::getInstance()->getDataPath() . $this->pathFolder . $file,
                $file
            );
        }
    }

    public function addFile(string $inPack, string $path): void {
        $this->archive->addFromString($path, $inPack);
        $this->checkSumSource .= md5_file($inPack);
    }

    public function addFromString(string $inPack, string $content): void {
        $this->archive->addFromString($inPack, $content);
        $this->checkSumSource .= $content;
    }

    public function generate(): ResourcePack {
        $this->injectManifest();
        $this->injectCustomJSON();
        $this->archive->close();
        return new ZippedResourcePack($this->path);
    }

    private function injectManifest(): void {
        $this->addFromString("manifest.json", JsonSerializer::serialize([
            'format_version' => 2,
            'header' => [
                'name' => 'EndioriteLobby Pack for ' . $this->name,
                'uuid' => Uuid::uuid3(self::UUID_PACK_NAMESPACE, $this->checkSumSource)->toString(),
                'description' => 'EndioriteLobby by JblusItsMe',
                'version' => [1, 0, 0],
                'min_engine_version' => [1, 16, 0],
                'author' => 'JblusItsMe#0001'
            ],
            'modules' => [
                [
                    'type' => 'resources',
                    'uuid' => Uuid::uuid3(self::UUID_RESOURCE_NAMESPACE, $this->checkSumSource)->toString(),
                    'version' => [1, 0, 0]
                ]
            ]
        ]));
    }

    private function injectCustomJSON(): void {
        //$this->addFile(Server::getInstance()->getDataPath() . "packFile/glyph_E0.png", "font/glyph_E0.png");
    }

}