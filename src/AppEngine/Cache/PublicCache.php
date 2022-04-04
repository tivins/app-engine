<?php

namespace Tivins\AppEngine\Cache;

use Tivins\AppEngine\Env;
use Tivins\Core\System\FileSys;

class PublicCache
{
    public static function url(string $path): string
    {
        $path = Env::PUBLIC_CACHE_PATH . $path;
        return Env::url($path, [
            'prefixLang' => false,
            'query' => [
                'pct' => Cache::getToken(),
            ],
        ]);
    }
    public static function path(string $name): string {
        return Env::getPublicPath(Env::PUBLIC_CACHE_PATH . '/' . ltrim($name,'/'));
    }
}