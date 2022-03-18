<?php

namespace Tivins\AppEngine;

use Throwable;
use Tivins\Core\Code\Exception;
use Tivins\Core\Http\{HTTP, QueryString, Request};
use Tivins\Core\{ OptionArg, OptionsArgs };
use Tivins\Database\Database;
use Tivins\Database\Exceptions\ConnectionException;

/**
 *
 *
 *
 *
 * @see Boot::init()
 */
class Boot
{
    /**
     * Initialize the application environment, such as per-host settings, session, database connection, ...etc.
     *
     * Usages :
     *
     * From index.php (ex in ROOT/public):
     * ```php
     * require __dir__ . '/../vendor/autoload.php';
     * Boot::init(__dir__ . '/..'); // Project's dir.
     * ```
     *
     * From script (ex in ROOT/bin):
     * ```php
     * require __dir__ . '/../vendor/autoload.php';
     * $options = new OptionsArgs(); // and add some OptionArg...
     * Boot::init(__dir__ . '/..', $options); // Project's dir.
     * ```
     *
     * @param string $rootDir
     *
     * @param ?OptionsArgs $optionsArgs
     *
     * @return array The result of OptionsArgs parsing (CLI Only) or an empty array.
     *
     * @throws Exception
     *
     * @see Boot::run()
     */
    public static function init(string $rootDir, ?OptionsArgs $optionsArgs = null): array
    {
        //
        // -- Exception handler
        //
        set_exception_handler(function (Throwable $exception) {
            if ($exception instanceof Exception) {
                error_log($exception->getPrivateMessage());
                die('Fatal error ' . $exception->getMessage());
            }
            var_dump($exception);
            die('Thrown! '.$exception->getMessage());
        });

        //
        // -- Main process
        //
        AppSettings::getInstance()->setRootDir($rootDir);
        $options = self::readSettingsFromCLI($optionsArgs);
        self::run();

        //
        // -- Return options values (for CLI)
        //
        return $options;
    }

    /**
     * @throws Exception
     */
    private static function run()
    {
        $appSettings = AppSettings::getInstance();

        //
        // -- Load settings
        //
        $domain = str_replace(['.', ':'], '-', $_SERVER['HTTP_HOST']);
        $settingsFilename = $appSettings->getRootDir() . '/settings/' . $domain . '.settings.php';
        if (! is_readable($settingsFilename)) {
            throw new Exception("Settings not found", $settingsFilename . " not found");
        }
        require_once $settingsFilename;

        //
        // -- Init session
        //
        session_name($appSettings->sessionName);
        session_start();

        //
        // -- Default 'logout'
        //
        if (isset($_GET['logout'])) {
            session_destroy();
            HTTP::redirect('/');
        }

        //
        // -- Query String
        //
        QueryString::parse();

        //
        // -- Database
        //
        if ($appSettings->connector) {
            try {
                AppData::setDatabase(new Database($appSettings->connector));
            } catch (ConnectionException $e) {
                throw new Exception('Cannot access database', $e->getMessage());
            }
        }
    }

    /**
     * If the request come from CLI, to find the right settings,we need to know the requested domain.
     * The argument `-u` or `--uri` should be defined. So, the `$_SERVER` variables could be set accordingly.
     *
     * @param OptionsArgs|null $optionsArgs
     *      A specific OptionsArgs can be provided. If $optionsArgs is null, a new OptionsArgs will be used instead.
     */
    private static function readSettingsFromCLI(?OptionsArgs $optionsArgs = null): array
    {
        if (!Request::isCLI()) {
            return [];
        }
        $args = ($optionsArgs ?? new OptionsArgs)->add(new OptionArg('u', true, 'uri'))->parse();

        if (empty($args['uri'])) {
            die('uri argument is missing' . PHP_EOL);
        }
        $uri = parse_url($args['uri']);

        //
        // -- Override $_SERVER variables
        //
        $_SERVER['HTTP_HOST']   = $uri['host'] . (':' . $uri['port']);
        $_SERVER['HTTPS']       = $uri['scheme'] == 'https' ? 'ON' : '';
        $_SERVER['SERVER_PORT'] = $uri['port'];
        $_SERVER['REQUEST_URI'] = '/';
        return $args;
    }
}