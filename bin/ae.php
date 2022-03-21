#!/usr/bin/env php
<?php

use Tivins\Core\System\FileSys;

/*
 * The script is wrapped by composer, so autoloader is already on.
 */
require $GLOBALS['_composer_autoload_path'];

/**
 *
 */
if (!isset($argv[0])) {
    die("Should be ran from CLI.\n");
}

/**
 *
 */
// if (!str_starts_with($argv[0], 'vendor/bin')) {
//     die("Should be ran from the root directory of the project.\n");
// }

$root = getcwd();
echo "Current working dir is '$root'." . PHP_EOL;

echo \Tivins\Core\System\Terminal::decorateInfo("Create directories") . PHP_EOL;
FileSys::mkdir("$root/bin");
FileSys::mkdir("$root/src");
FileSys::mkdir("$root/files/cache");
FileSys::mkdir("$root/templates");
FileSys::mkdir("$root/public");
FileSys::mkdir("$root/settings");

echo \Tivins\Core\System\Terminal::decorateInfo('Add to .ignore') . PHP_EOL;
file_put_contents("$root/.gitignore",
    "/vendor/\n"
    . "/settings/*.php\n"
    . "/files/cache\n"
    , FILE_APPEND
);

$body = '<?php' . "\n\n";
$body .= 'require __dir__ . \'/../vendor/autoload.php\';' . "\n\n";
$body .= 'Tivins\AppEngine\Boot::init(__dir__ . \'/..\');' . "\n\n";
FileSys::writeFile("$root/public/index.php", $body);
echo \Tivins\Core\System\Terminal::decorateInfo('public/index.php created') . PHP_EOL;

copy(__dir__ . '/../files/models/default.settings.php', "$root/settings/localhost.settings.php");

copy(__dir__ . '/../files/models/html.html', "$root/templates/html.html");
copy(__dir__ . '/../files/models/page_maintenance.html', "$root/templates/page_maintenance.html");
