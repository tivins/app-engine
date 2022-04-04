<?php

namespace Tivins\AppEngine;

class Controller
{
    public const SERVICE = '';

    /**
     * Return the default URL for the service.
     */
    public static function url(string $path): string
    {
        return Env::url(static::SERVICE . '/' . $path);
    }
}