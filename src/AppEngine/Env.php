<?php

namespace Tivins\AppEngine;

class Env
{
    public const DIR_TPL         = '/templates';
    public const DIR_FILES       = '/files';
    public const DIR_PUBLIC      = '/public';
    public const DIR_PUBLIC_META = self::DIR_PUBLIC . '/assets/meta';

    private static string $ROOT_DIR = '';

    public static function getRootDir(): string
    {
        return self::$ROOT_DIR;
    }

    public static function setRootDir(string $dir)
    {
        self::$ROOT_DIR = $dir;
    }

    public static function getFilePath(string $name): string
    {
        return self::getPath(self::DIR_FILES . '/' . ltrim($name, '/'));
    }

    /**
     * Path from ROOT_DIR.
     *
     * @param string $path
     * @return string
     * @see setRootDir()
     */
    public static function getPath(string $path = '/'): string
    {
        return self::$ROOT_DIR . '/' . ltrim($path, '/');
    }

    public static function getTemplatePath(string $name): string
    {
        return self::getPath(self::DIR_TPL . '/' . ltrim($name, '/'));
    }

    public static function getPublicPath(string $name): string
    {
        return self::getPath(self::DIR_PUBLIC . '/' . ltrim($name, '/'));
    }

    // --- URL ---

    public static function getURL($path = '/', array $options = []): string
    {
        $options += ['absolute' => false];
        $path    = '/' . AppData::getLanguageCode() . '/' . ltrim($path, '/');
        if ($options['absolute']) {
            $path = self::getAbsoluteURL($path);
        }
        return $path;
    }

    public static function getAbsoluteURL($path = '/'): string
    {
        return 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($path, '/');
    }

}