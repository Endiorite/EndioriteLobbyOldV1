<?php

namespace endiorite\database;

use endiorite\Main;

class MySQL {

    public function getConnection(): \mysqli {
        return new \mysqli(
            Main::DB_HOST,
            Main::DB_USER,
            Main::DB_PASS,
            Main::DB_SESSION,
            Main::DB_PORT
        );
    }

    // uuid, username, ip, deviceOs, client informations, dernière connection, première connection, temps de jeux

    public function createTables(): void {
        $conn = $this->getConnection();
        $conn->query("CREATE TABLE IF NOT EXISTS accounts (" .
            "`uuid` VARCHAR(255) PRIMARY KEY, " .
            "`username` VARCHAR(255), " .
            "`ip` VARCHAR(255), " .
            "`deviceOS` VARCHAR(255), " .
            "`client` VARCHAR(255), " .
            "`lastLogin` VARCHAR(255), " .
            "`firstLogin` VARCHAR(255), " .
            "`playerTime` INT)"
        );
    }

}