<?php

namespace Tivins\AppEngine\Cache;

use Tivins\AppEngine\Env;

class PrivateCache
{
    /**
     * Return the default PATH for the service.
     */
    public static function path(string $path): string
    {
        return Env::path(Env::PRIVATE_CACHE_DIR.'/'. $path);
    }
}