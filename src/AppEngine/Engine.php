<?php

namespace Tivins\AppEngine;

use Tivins\Core\Code\Exception;
use Tivins\Core\Tpl;

class Engine
{
    public const DIR_TPL   = '/templates';
    public const FILES_TPL = '/files';

    public static function getTemplate(string $name): Tpl
    {
        $root = AppSettings::getInstance()->getRootDir();
        $tpl  = Tpl::fromFile($root . self::DIR_TPL . '/' . $name, true);
        // $tpl->addFunction('$name', $callback);
        return $tpl;
    }

    public static function getFilePath(string $name): string
    {
        $root = AppSettings::getInstance()->getRootDir();
        return $root . self::FILES_TPL . ltrim($name, '/');
    }

    /**
     * @todo remove die.
     */
    public static function throwableHandler(\Throwable $exception)
    {
        error_log($exception->getTraceAsString());
        if ($exception instanceof Exception) {
            error_log($exception->getPrivateMessage());
            die('Fatal error ' . $exception->getMessage());
        }
        die('Thrown! ' . $exception->getMessage());
    }
}