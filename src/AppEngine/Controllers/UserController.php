<?php

namespace Tivins\AppEngine\Controllers;

use Tivins\AppEngine\AppData;
use Tivins\AppEngine\Controller;
use Tivins\AppEngine\Engine;
use Tivins\Core\APIAccess;
use Tivins\Core\Http\HTTP;
use Tivins\Core\Http\Method;
use Tivins\Core\Msg;
use Tivins\UserPack\UserForm;
use Tivins\UserPack\UserSession;


class UserController extends Controller
{
    public const SERVICE = 'user';

    #[APIAccess(Method::GET, 'public')]
    public static function index(): never
    {
        if (UserSession::isAuthenticated()) {
            $settings = AppData::settings();
            $tpl = Engine::getTemplate('page-single.html');
            $tpl->setVars([
                'title'    => $settings->getTitle(),
                'titleAlt' => $settings ->getPunchLine(),
                'body'     => AppData::msg()->get() . 'Welcome ' . UserSession::getUser(AppData::usermod())->name . '<br><a href="' . static::getPath('logout') . '">log out</a>',
                'homeURL'  => '/',
            ]);
            AppData::htmlPage()->deliver($tpl);
        }
        HTTP::redirect(static::getPath('login'));
    }

    #[APIAccess(Method::GET, 'public')]
    public static function login(): never
    {
        $settings = AppData::settings();

        if (UserSession::isAuthenticated()) {
            AppData::msg()->push('Already authenticated', Msg::Success);
            HTTP::redirect(static::getPath(''));
        }
        $userForm = new UserForm();
        $userForm->setActionURI($_SERVER['REQUEST_URI']);
        $userForm->loginCheck(AppData::usermod(), $_SERVER['REQUEST_URI']);
        $body = '<div class="block p-md">'
            . '<h3 class="mt-0">Sign in</h3>'
            . $userForm->login()
            . '</div>'
            .'<a href="' . static::getPath('register') . '" class="d-block fs-90 py-md text-center fake-link">New here ? <span>sign up</span></a>'
            ;
        $tpl  = Engine::getTemplate('page-single.html');
        $tpl->setVars([
            'title'    => AppData::settings()->getTitle(),
            'titleAlt' => AppData::settings()->getPunchLine(),
            'body'     => $body,
            'homeURL'  => '/',
        ]);
        AppData::htmlPage()->setTitle('Sign in to ' . $settings->getTitle())->deliver($tpl);
    }

    #[APIAccess(Method::GET, 'public')]
    public static function register(): never
    {
        $settings = AppData::settings();
        $userForm = new UserForm();
        $userForm->setActionURI($_SERVER['REQUEST_URI']);
        $userForm->registerCheck(AppData::usermod(), $_SERVER['REQUEST_URI']);
        $body = '<div class="block p-md">'
            .'<h3 class="mt-0">Register</h3>'
            . $userForm->register()
            . '</div>'
            . '<a href="' . static::getPath('login') . '" class="d-block fs-90 py-md text-center fake-link">Already have an account ? <span>sign in</span></a>';
        $tpl  = Engine::getTemplate('page-single.html');
        $tpl->setVars([
            'title'    => AppData::settings()->getTitle(),
            'titleAlt' => AppData::settings()->getPunchLine(),
            'body'     => $body,
            'homeURL'  => '/',
        ]);
        AppData::htmlPage()->setTitle('Sign up to ' . $settings->getTitle())->deliver($tpl);
    }

    #[APIAccess(Method::GET, 'public')]
    public static function logout(): never
    {
        Engine::logout();
    }
}