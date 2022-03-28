<?php

namespace Tivins\AppEngine;

use Throwable;
use Tivins\Core\APIAccess;
use Tivins\Core\Code\Exception;
use Tivins\Core\Http\HTTP;
use Tivins\Core\Http\QueryString;
use Tivins\Core\Msg;
use Tivins\Core\Tpl;
use Tivins\I18n\I18n;

class Engine
{
    private static I18n $i18n;

    /**
     * @var Controller[]
     */
    private static array $controllers = [];

    public static function getTemplate(string $name): Tpl
    {
        $tpl = Tpl::fromFile(Env::getTemplatePath($name), true);
        $tpl->setVars([
            'SiteTitle'     => AppData::settings()->getTitle(),
            'SitePunchLine' => AppData::settings()->getPunchLine(),
            'SiteHomeURL'   => Env::getAbsoluteURL(),
        ]);
        // $tpl->addFunction('$name', $callback);
        return $tpl;
    }


    /**
     * @todo remove die.
     */
    public static function throwableHandler(Throwable $exception): never
    {

        echo '<pre>' . var_export($exception, 1) . '</pre>';
        if ($exception instanceof Exception) {
            error_log($exception->getPrivateMessage());
            die('Fatal error ' . $exception->getMessage());
        }
        die('Thrown! ' . $exception->getMessage());
    }

    public static function start(string $rootDir)
    {
        Boot::init($rootDir);
        Engine::run();
    }

    /**
     */
    public static function run()
    {
        $controllerClass = self::$controllers[QueryString::at(0)] ?? false;
        if (!$controllerClass) {
            self::fatalError('404-1');
        }
        $controllerReflect = new \ReflectionClass($controllerClass);
        if ($controllerReflect->getParentClass()?->getName() !== Controller::class) {
            self::fatalError('401-2');
        }

        try {
            $method = new \ReflectionMethod($controllerClass, QueryString::at(1) ?: 'index');
        } catch (\ReflectionException $e) {
            self::fatalError('404-4');
        }
        $attrs = $method->getAttributes(APIAccess::class);
        if (empty($attrs)) {
            self::fatalError('401-3');
        }
        $method->invoke(null,);
    }

    public static function fatalError($code): never
    {
        AppData::msg()->push('Error ' . $code, Msg::Error);
        HTTP::redirect('/');
    }

    public static function addController(string $name, string $class)
    {
        self::$controllers[trim($name, '/')] = $class;
    }

    /**
     * @return Controller[]
     */
    public static function getControllers(): array
    {
        return self::$controllers;
    }

    public static function logout(): never
    {
        session_destroy();
        HTTP::redirect('/');
    }
}