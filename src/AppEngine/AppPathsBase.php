<?php

namespace Tivins\AppEngine;

use BackedEnum;
use Tivins\Core\Http\HTTP;

trait AppPathsBase
{
    public static function redirect(BackedEnum $path)
    {
        HTTP::redirect(self::getPath($path));
    }

    public static function getPath(BackedEnum $path): string
    {
        return '/' . $path->value;
    }
}
