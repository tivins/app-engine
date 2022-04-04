<?php

namespace Tivins\AppEngine;

use Tivins\Core\Code\Exception;

class Env
{
    public const DIR_TPL               = '/templates';
    public const DIR_FILES             = '/files';
    public const PRIVATE_CACHE_DIR     = '/files/cache';

    public const DIR_PUBLIC            = '/public';
    public const PUBLIC_CACHE_PATH     = '/cache'; // inside DIR_PUBLIC.
    public const DIR_PUBLIC_CACHE_META = '/meta'; // inside PUBLIC_CACHE_PATH
    public const META_WEBMANIFEST      = 'manifest.json'; // inside DIR_PUBLIC_CACHE_META

    public const ENGINE_DIR            = __dir__ . '/../..';
    public const ENGINE_CSS_DIR        = self::ENGINE_DIR . '/files/defaults/css';
    public const ENGINE_TPL_DIR        = self::ENGINE_DIR . '/files/defaults/html';

    private static string $ROOT_DIR = '';

    //----

    /**
     * Path from ROOT_DIR.
     *
     * @param string $path
     * @return string
     * @see setRootDir()
     */
    public static function path(string $path): string
    {
        return self::$ROOT_DIR . '/' . ltrim($path, '/');
    }

    public static function url($path = '/', array $options = []): string
    {
        $options += [
            'absolute' => false,
            'prefixLang' => true,
            'query' => []
        ];
        $options['query']['ae'] = 'ae';
        $out = '';
        if ($options['prefixLang']) {
            $out .= '/' . AppData::getLanguageCode();
        }
        $out .= '/' . $path;
        $out = str_replace('//','/', $out);
        if (!empty($options['query'])) {
            $out .= '?' . http_build_query($options['query']);
        }
        if ($options['absolute']) {
            $out = self::getAbsoluteURL() . $out;
        }
        return $out;
    }

    //----

    public static function init(string $dir)
    {
        self::$ROOT_DIR = realpath($dir);
    }

    public static function getFilePath(string $name): string
    {
        return self::path(self::DIR_FILES . '/' . ltrim($name, '/'));
    }

    public static function getTemplatePath(string $name): string
    {
        $path = self::path(self::DIR_TPL . '/' . ltrim($name, '/'));
        if (is_readable($path)) return $path;
        $path = self::ENGINE_TPL_DIR . '/' . ltrim($name, '/');
        if (is_readable($path)) return $path;
        /* @todo throw exception? */
        throw new Exception('Cannot load template', 'cannot load template ' . $name);
    }

    public static function getPublicPath(string $name): string
    {
        return self::path(self::DIR_PUBLIC . '/' . ltrim($name, '/'));
    }

    // --- URL ---


    private static function getAbsoluteURL(): string
    {
        return 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
    }

    public static function getWebmanifestCachePath(): string
    {
        return self::DIR_PUBLIC_CACHE_META . '/' . self::META_WEBMANIFEST;
    }

}