<?php

namespace Tivins\AppEngine\Controllers;

use Tivins\AppEngine\AppData;
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
        $tpl = Engine::getTemplate('page-single.html');
        $tpl->setVars([
            'body' => AppData::msg()->get().'Home '.(UserSession::isAuthenticated()?'yes':'no').'<br><a href="'.UserController::getPath('login').'">sign in</a>',
        ]);
        AppData::htmlPage()->deliver($tpl);
    }
}