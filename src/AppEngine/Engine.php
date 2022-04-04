<?php

namespace Tivins\AppEngine;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;
use Tivins\AppEngine\Cache\PublicCache;
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
        $tpl = Tpl::fromFile(Env::getTemplatePath($name), false);
        $tpl->addIncludeDirectory(Env::path(Env::ENGINE_TPL_DIR));
        $tpl->addIncludeDirectory(Env::path(Env::DIR_TPL));
        $tpl->setVars([
            'SiteTitle'     => AppData::settings()->getTitle(),
            'SitePunchLine' => AppData::settings()->getPunchLine(),
            'SiteHomeURL'   => Env::url('/', [
                'absolute'   => true,
                'prefixLang' => true,
                'query'      => []
            ]),
            'SiteLogo64'    => PublicCache::url(Env::DIR_PUBLIC_CACHE_META . '/' . Meta::getIconName(64)),

        ]);
        foreach ([16, 32, 64, 180] as $size) {
            $tpl->setVar('SiteIconURL' . $size, PublicCache::url(Env::DIR_PUBLIC_CACHE_META . '/' . Meta::getIconName($size)));
        }
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

    public static function start(string $rootDir): never
    {
        Boot::init($rootDir);
        Engine::run();
    }

    /**
     * Find matching controller from the current URL path.
     * @throws ReflectionException
     */
    public static function run(): never
    {
        $controllerClass = self::$controllers[QueryString::at(0)] ?? false;
        if (!$controllerClass) {
            self::fatalError('404-1');
        }
        $controllerReflect = new ReflectionClass($controllerClass);
        if ($controllerReflect->getParentClass()?->getName() !== Controller::class) {
            self::fatalError('401-2');
        }

        QueryString::shift();

        try {
            $method = new ReflectionMethod($controllerClass, QueryString::at(0) ?: 'index');
        } catch (ReflectionException $e) {
            self::fatalError('404-4');
        }

        $attrs = $method->getAttributes(APIAccess::class);
        if (empty($attrs)) {
            self::fatalError('401-3');
        }
        QueryString::shift();
        $method->invoke(null);

        self::fatalError('404-5');
    }

    public static function fatalError($code): never
    {
        AppData::msg()->push('Error ' . $code, Msg::Error);
        HTTP::redirect('/');
    }

    public static function addController(string $class)
    {
        self::$controllers[trim(constant($class . '::SERVICE'), '/')] = $class;
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