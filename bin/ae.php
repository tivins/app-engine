#!/usr/bin/env php
<?php

use Tivins\Core\System\FileSys;
use Tivins\Core\System\Terminal;

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

echo Terminal::decorateInfo("Create directories") . PHP_EOL;
FileSys::mkdir("$root/bin");
FileSys::mkdir("$root/src");
FileSys::mkdir("$root/files/cache");
FileSys::mkdir("$root/templates");
FileSys::mkdir("$root/public");
FileSys::mkdir("$root/settings");

$gitIgnore = "/vendor/\n"
    . "/settings/*.php\n"
    . "/files/cache\n";

$index = '<?php' . "\n\n";
$index .= 'require __dir__ . \'/../vendor/autoload.php\';' . "\n\n";
$index .= 'Tivins\AppEngine\Boot::init(__dir__ . \'/..\');' . "\n\n";


createFileAndGitAdd("$root/.gitignore", $gitIgnore, true);
createFileAndGitAdd("$root/public/index.php", $index);
copy(__dir__ . '/../files/models/default.settings.php', "$root/settings/localhost.settings.php");
copyFileAndGitAdd(__dir__ . '/../files/models/html.html', "$root/templates/html.html");
copyFileAndGitAdd(__dir__ . '/../files/models/page_maintenance.html', "$root/templates/page_maintenance.html");
copyFileAndGitAdd(__dir__ . '/../files/models/engine.js', "$root/public/assets/engine/engine.js");
copyFileAndGitAdd(__dir__ . '/../files/models/engine.css', "$root/public/assets/engine/engine.css");


function createFileAndGitAdd($dst, $content, bool $append = false)
{
    FileSys::writeFile($dst, $content, $append);
    echo Terminal::decorateInfo("$dst created.") . PHP_EOL;
    gitAdd($dst);
}
function copyFileAndGitAdd($src,$dst)
{
    copy($src, $dst);
    echo Terminal::decorateInfo("$dst created.") . PHP_EOL;
    gitAdd($dst);
}
function gitAdd($file)
{
    shell_exec("git add $file");
    echo Terminal::decorateInfo("$file added to git.") . PHP_EOL;
}