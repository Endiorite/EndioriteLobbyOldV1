<?php

namespace endiorite\async;

use endiorite\database\MySQL;
use endiorite\Main;
use pocketmine\scheduler\AsyncTask;

class MySqlAsync extends AsyncTask {

    protected string $query;

    public function __construct(string $query) {
        $this->query = $query;
    }

    public function onRun(): void {
        (new MySQL())->getConnection()->query($this->query);
    }

}