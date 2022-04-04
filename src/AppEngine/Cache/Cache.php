<?php

namespace Tivins\AppEngine\Cache;

use Tivins\AppEngine\Env;
use Tivins\Core\System\FileSys;

class Cache
{
    private static string $token = '';

    public static function getTokenPath(): string
    {
        return Env::path(Env::PRIVATE_CACHE_DIR . '/public-cache-token');
    }

    public static function regenerate(): string
    {
        $token = substr(sha1(time()), -8);
        if (!FileSys::writeFile(self::getTokenPath(), $token)) {
            /* @todo manage error */
        }
        return $token;
    }

    public static function getToken(): string
    {
        $tokenPath = self::getTokenPath();
        $token     = FileSys::loadFile($tokenPath);
        if (!$token) {
            $token = self::regenerate();
        }
        return $token;
    }
}