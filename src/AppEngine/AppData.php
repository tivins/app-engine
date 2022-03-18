<?php

namespace Tivins\AppEngine;

use Tivins\Database\Database;

class AppData
{
    private static Database $db;

    public static function setDatabase(Database $db): void
    {
        self::$db = $db;
    }

    public static function db(): Database
    {
        return self::$db;
    }
}
