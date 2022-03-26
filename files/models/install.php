<?php

use Tivins\AppEngine\AppData;
use Tivins\AppEngine\Boot;
use Tivins\Core\OptionsArgs;
use Tivins\Database\Database;

require __dir__ . '/../vendor/autoload.php';

$options = new OptionsArgs(); // and add some OptionArg...

$args = Boot::init(__dir__ . '/..', $options); // Project's dir.
install(AppData::db());

function install(Database $db) {

}