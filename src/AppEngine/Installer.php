<?php

namespace Tivins\AppEngine;

use Tivins\Database\Database;

abstract class Installer
{
    public function __construct(protected Database $db)
    {
    }

    abstract function install();
}