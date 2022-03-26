<?php

namespace Tivins\AppEngine;

class Controller
{
    public const SERVICE = '';

    public static function getPath(string $path): string
    {
        return '/' . static::SERVICE . '/' . ltrim($path, '/');
    }
}