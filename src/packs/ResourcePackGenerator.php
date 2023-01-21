<?php

namespace packs;

use pocketmine\resourcepacks\ResourcePack;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use Ramsey\Uuid\Uuid;

class ResourcePackGenerator implements IPackSupplier {

    private const UUID_PACK_NAMESPACE = "21c23023-26e8-94d0-afb9-826af56220a1";
    private const UUID_RESOURCE_NAMESPACE = "6ccc0e32-0252-4ad5-be00-afe609d9402c";

    private \ZipArchive $archive;
    private string $checkSumSource = "";

    public function __construct(private string $path, private string $name) {
        @unlink($this->path);
        $this->archive = new \ZipArchive();
        $this->archive->open($this->path, \ZipArchive::CREATE);
        $this->archive->addEmptyDir("font");
        $this->archive->addFile(
            Server::getInstance()->getDataPath() . "packFile/glyph_E0.png",
            "font/glyph_E0.png"
        );
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
                'name' => 'NoLook Pack for ' . $this->name,
                'uuid' => Uuid::uuid3(self::UUID_PACK_NAMESPACE, $this->checkSumSource)->toString(),
                'description' => 'NoLook by JblusItsMe',
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