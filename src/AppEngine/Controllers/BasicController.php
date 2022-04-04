<?php

namespace Tivins\AppEngine\Controllers;

use Tivins\AppEngine\AppData;
use Tivins\AppEngine\Cache\Cache;
use Tivins\AppEngine\Controller;
use Tivins\AppEngine\Engine;
use Tivins\Core\APIAccess;
use Tivins\Core\Http\Method;
use Tivins\UserPack\UserSession;

class BasicController extends Controller
{
    public const SERVICE = '';

    #[APIAccess(Method::GET, 'public')]
    public static function index(): never
    {
        $tpl = Engine::getTemplate('empty.html');
        $tpl->setVars([
            'body' =>
                AppData::msg()->get()
                . 'Home '
                . '<br>Lang: ' . AppData::getLanguage()->name
                . '<h2>Admin</h2>'
                . self::getUserAdminModule()
                . self::getCacheAdminModule()
            ,
        ]);
        AppData::htmlPage()->deliver($tpl);
    }

    public static function getUserAdminModule(): string {
        return '<div class="block p-md mb-md"><h3 class="my-0">USER</h3>'
            . 'Logged: ' . (UserSession::isAuthenticated() ? 'yes' : 'no')
            . '<br><a href="' . UserController::url('login') . '">sign in</a>'
            . '</div>'
            ;
    }
    public static function getCacheAdminModule(): string {
        return '<div class="block p-md mb-md"><h3 class="my-0">CACHE</h3>'
        . 'Token is '. Cache::getToken()
        . '<br>Up from '. date('Y-m-d H:i:s T', filemtime(Cache::getTokenPath()))
        . '<br>Path is '. Cache::getTokenPath()
        . '<br><a href="' . CacheController::url('rebuild') . '">clear cache</a>'
        . '</div>'
        ;
    }
}