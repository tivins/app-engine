<?php

namespace Tivins\AppEngine;

use Tivins\Core\Tpl;

class Engine
{
    public const DIR_TPL   = '/templates';
    public const FILES_TPL = '/files';

    public static function getTemplate(string $name): Tpl
    {
        $root = AppSettings::getInstance()->getRootDir();
        $tpl = Tpl::fromFile($root . self::DIR_TPL . '/' . $name, true);
        // $tpl->addFunction('$name', $callback);
        return $tpl;
    }

    public static function getFilePath(string $name): string
    {
        $root = AppSettings::getInstance()->getRootDir();
        return $root . self::FILES_TPL . ltrim($name, '/');
    }
}