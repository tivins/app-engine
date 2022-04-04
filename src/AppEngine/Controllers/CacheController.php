<?php

namespace Tivins\AppEngine\Controllers;

use Tivins\AppEngine\AppData;
use Tivins\AppEngine\Cache\Cache;
use Tivins\AppEngine\Cache\PublicCache;
use Tivins\AppEngine\Controller;
use Tivins\AppEngine\Env;
use Tivins\AppEngine\Meta;
use Tivins\Core\APIAccess;
use Tivins\Core\Http\ContentType;
use Tivins\Core\Http\HTTP;
use Tivins\Core\Http\Method;
use Tivins\Core\Http\QueryString;
use Tivins\Core\Msg;
use Tivins\Core\System\FileSys;


class CacheController extends Controller
{
    public const SERVICE = 'cache';

    /*
    #[Route(
        methods: [Method::GET],
        permissions: ['public'],
        path: 'js',
        accept: [ContentType::JS],
    )]
    */
    #[APIAccess(Method::GET, 'public')]
    public static function rebuild()
    {
        Cache::regenerate();
        Meta::build(true);
        AppData::msg()->push('Cache rebuilt', Msg::Success);
        HTTP::redirect(Env::url());
    }

    #[APIAccess(Method::GET, 'public')]
    public static function meta(): never
    {
        switch (QueryString::at(0)) {
            case Env::META_WEBMANIFEST:
                Meta::build();
                $body = FileSys::loadFile(PublicCache::path(Env::getWebmanifestCachePath()));
                HTTP::send($body, ContentType::JSON);
            default:
                die('404--8');
        }
    }
    #[APIAccess(Method::GET, 'public')]
    public static function css(): never
    {
        if (QueryString::at(0) == 'engine') {
            QueryString::shift();
            $path = QueryString::all();
            $css = FileSys::loadFile(Env::ENGINE_CSS_DIR.'/'.$path);
            HTTP::send($css, ContentType::CSS);
        }
        $path = QueryString::all();
        var_dump($path);die;
    }
}